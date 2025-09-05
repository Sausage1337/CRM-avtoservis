<?php
class UserController {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function roles() {
        $this->ensureAdmin();
        $users = $this->db->fetchAll("SELECT id, username, full_name, role FROM users ORDER BY username");
        $this->renderView('users/roles', ['users' => $users]);
    }

    public function updateRole() {
        $this->ensureAdmin();
        $userId = (int)($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? 'manager';
        if ($userId) {
            $this->db->execute("UPDATE users SET role = ? WHERE id = ?", [$role, $userId]);
        }
        header('Location: index.php?page=users');
        exit();
    }

    private function ensureAdmin() {
        if (($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: index.php?page=dashboard&error=access');
            exit();
        }
    }

    private function renderView($view, $data = []) {
        global $page; if (!isset($page)) { $page = 'users'; }
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>


