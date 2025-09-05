<?php
class AuthController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $error = 'Введите имя пользователя и пароль';
            } else {
                $user = $this->db->fetch(
                    "SELECT * FROM users WHERE username = ?",
                    [$username]
                );
                
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'] ?? 'Пользователь';
                    $_SESSION['full_name'] = $user['full_name'] ?? $user['username'] ?? 'Пользователь';
                    $_SESSION['role'] = $user['role'] ?? 'user';
                    
                    header('Location: index.php?page=dashboard');
                    exit();
                } else {
                    $error = 'Неверное имя пользователя или пароль';
                }
            }
        }
        
        $this->renderView('auth/login', ['error' => $error ?? null]);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $full_name = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            
            // Валидация
            $errors = [];
            
            if (empty($username)) $errors[] = 'Имя пользователя обязательно';
            if (empty($password)) $errors[] = 'Пароль обязателен';
            if ($password !== $confirm_password) $errors[] = 'Пароли не совпадают';
            if (strlen($password) < 6) $errors[] = 'Пароль должен быть не менее 6 символов';
            if (empty($full_name)) $errors[] = 'Полное имя обязательно';
            
            // Проверка уникальности имени пользователя
            if (!empty($username)) {
                $existingUser = $this->db->fetch(
                    "SELECT id FROM users WHERE username = ?",
                    [$username]
                );
                if ($existingUser) {
                    $errors[] = 'Пользователь с таким именем уже существует';
                }
            }
            
            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
                
                try {
                    $this->db->insert('users', [
                        'username' => $username,
                        'password' => $hashedPassword,
                        'full_name' => $full_name,
                        'email' => $email,
                        'role' => 'manager'
                    ]);
                    
                    $success = 'Регистрация успешна! Теперь вы можете войти в систему.';
                } catch (Exception $e) {
                    $errors[] = 'Ошибка при создании пользователя: ' . $e->getMessage();
                }
            }
        }
        
        $this->renderView('auth/register', [
            'errors' => $errors ?? [],
            'success' => $success ?? null,
            'formData' => $_POST ?? []
        ]);
    }
    
    private function renderView($view, $data = []) {
        // Для страниц авторизации используем их собственные шаблоны без основного layout
        extract($data);
        include VIEWS_PATH . "/{$view}.php";
    }
}
?>
