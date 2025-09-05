<?php
class ClientAuthController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone'] ?? '';
            $orderNumber = $_POST['order_number'] ?? '';
            
            if (empty($phone) || empty($orderNumber)) {
                $error = 'Введите номер телефона и номер заказа';
            } else {
                // Ищем клиента по телефону и заказу
                $order = $this->db->fetch(
                    "SELECT o.*, c.id as client_id, c.full_name, c.phone, c.email 
                     FROM orders o 
                     JOIN clients c ON o.client_id = c.id 
                     WHERE c.phone = ? AND o.order_number = ?",
                    [$phone, $orderNumber]
                );
                
                if ($order) {
                    // Создаем сессию для клиента
                    $_SESSION['client_id'] = $order['client_id'];
                    $_SESSION['client_name'] = $order['full_name'];
                    $_SESSION['client_phone'] = $order['phone'];
                    
                    header('Location: index.php?page=client_portal');
                    exit();
                } else {
                    $error = 'Неверный номер телефона или номер заказа';
                }
            }
        }
        
        $this->renderView('client_auth/login', ['error' => $error ?? null]);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['full_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $licensePlate = $_POST['license_plate'] ?? '';
            
            // Валидация
            $errors = [];
            
            if (empty($fullName)) $errors[] = 'Полное имя обязательно';
            if (empty($phone)) $errors[] = 'Номер телефона обязателен';
            if (empty($licensePlate)) $errors[] = 'Госномер автомобиля обязателен';
            
            // Проверка уникальности телефона
            if (!empty($phone)) {
                $existingClient = $this->db->fetch(
                    "SELECT id FROM clients WHERE phone = ?",
                    [$phone]
                );
                if ($existingClient) {
                    $errors[] = 'Клиент с таким номером телефона уже существует';
                }
            }
            
            // Проверка существования автомобиля
            if (!empty($licensePlate)) {
                $existingVehicle = $this->db->fetch(
                    "SELECT id FROM vehicles WHERE license_plate = ?",
                    [$licensePlate]
                );
                if ($existingVehicle) {
                    $errors[] = 'Автомобиль с таким госномером уже зарегистрирован';
                }
            }
            
            if (empty($errors)) {
                try {
                    // Создаем клиента
                    $clientId = $this->db->insert('clients', [
                        'full_name' => $fullName,
                        'phone' => $phone,
                        'email' => $email
                    ]);
                    
                    // Создаем автомобиль
                    $vehicleId = $this->db->insert('vehicles', [
                        'license_plate' => $licensePlate,
                        'client_id' => $clientId
                    ]);
                    
                    // Создаем тестовый заказ для входа
                    $orderNumber = 'TEST-' . date('Ymd') . '-' . rand(1000, 9999);
                    $orderId = $this->db->insert('orders', [
                        'order_number' => $orderNumber,
                        'client_id' => $clientId,
                        'vehicle_id' => $vehicleId,
                        'status' => 'new',
                        'total_amount' => 0
                    ]);
                    
                    $success = 'Регистрация успешна! Номер вашего заказа: <strong>' . $orderNumber . '</strong><br>Теперь вы можете войти в личный кабинет.';
                } catch (Exception $e) {
                    $errors[] = 'Ошибка при создании аккаунта: ' . $e->getMessage();
                }
            }
        }
        
        $this->renderView('client_auth/register', [
            'errors' => $errors ?? [],
            'success' => $success ?? null,
            'formData' => $_POST ?? []
        ]);
    }
    
    public function logout() {
        // Удаляем сессию клиента
        unset($_SESSION['client_id']);
        unset($_SESSION['client_name']);
        unset($_SESSION['client_phone']);
        
        header('Location: index.php?page=client_login');
        exit();
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone'] ?? '';
            
            if (empty($phone)) {
                $error = 'Введите номер телефона';
            } else {
                // Проверяем существование клиента
                $client = $this->db->fetch(
                    "SELECT id, full_name FROM clients WHERE phone = ?",
                    [$phone]
                );
                
                if ($client) {
                    // Здесь можно добавить отправку SMS с кодом восстановления
                    // Пока просто показываем сообщение об успехе
                    $success = 'Инструкции по восстановлению доступа отправлены на ваш номер телефона';
                } else {
                    $error = 'Клиент с таким номером телефона не найден';
                }
            }
        }
        
        $this->renderView('client_auth/forgot_password', [
            'error' => $error ?? null,
            'success' => $success ?? null
        ]);
    }
    
    private function renderView($view, $data = []) {
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/client_auth_layout.php";
    }
}
?>
