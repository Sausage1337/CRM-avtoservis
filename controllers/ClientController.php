<?php
class ClientController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $search = $_GET['search'] ?? '';
        $clients = $this->getClients($search);
        
        $this->renderView('clients/index', [
            'clients' => $clients,
            'search' => $search
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
        } else {
            $this->renderView('clients/create');
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=clients');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($id);
        } else {
            $client = $this->getClientById($id);
            if (!$client) {
                header('Location: index.php?page=clients');
                exit;
            }
            
            $this->renderView('clients/edit', ['client' => $client]);
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->handleDelete($id);
        }
        header('Location: index.php?page=clients');
        exit;
    }
    
    public function view() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=clients');
            exit;
        }
        
        $client = $this->getClientById($id);
        if (!$client) {
            header('Location: index.php?page=clients');
            exit;
        }
        
        $vehicles = $this->getClientVehicles($id);
        $orders = $this->getClientOrders($id);
        
        $this->renderView('clients/view', [
            'client' => $client,
            'vehicles' => $vehicles,
            'orders' => $orders
        ]);
    }
    
    private function handleCreate() {
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'birth_date' => $_POST['birth_date'] ?? null,
            'notes' => trim($_POST['notes'] ?? ''),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Валидация
        $errors = $this->validateClientData($data);
        if (!empty($errors)) {
            $this->renderView('clients/create', [
                'errors' => $errors,
                'oldData' => $data
            ]);
            return;
        }
        
        try {
            $this->db->insert('clients', $data);
            $_SESSION['success_message'] = 'Клиент успешно создан!';
            header('Location: index.php?page=clients');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Ошибка при создании клиента: ' . $e->getMessage();
            $this->renderView('clients/create', [
                'errors' => $errors,
                'oldData' => $data
            ]);
        }
    }
    
    private function handleEdit($id) {
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'birth_date' => $_POST['birth_date'] ?? null,
            'notes' => trim($_POST['notes'] ?? ''),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Валидация
        $errors = $this->validateClientData($data);
        if (!empty($errors)) {
            $client = $this->getClientById($id);
            $this->renderView('clients/edit', [
                'client' => $client,
                'errors' => $errors,
                'oldData' => $data
            ]);
            return;
        }
        
        try {
            // В update используются именованные плейсхолдеры для SET,
            // поэтому и WHERE должен быть с именованным плейсхолдером
            $this->db->update('clients', $data, 'id = :id', ['id' => $id]);
            $_SESSION['success_message'] = 'Данные клиента успешно обновлены!';
            header('Location: index.php?page=clients');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Ошибка при обновлении клиента: ' . $e->getMessage();
            $client = $this->getClientById($id);
            $this->renderView('clients/edit', [
                'client' => $client,
                'errors' => $errors,
                'oldData' => $data
            ]);
        }
    }
    
    private function handleDelete($id) {
        // Проверяем, есть ли связанные записи
        $vehicles = $this->getClientVehicles($id);
        $orders = $this->getClientOrders($id);
        
        if (!empty($vehicles) || !empty($orders)) {
            $_SESSION['error_message'] = 'Невозможно удалить клиента с связанными записями!';
            return;
        }
        
        try {
            $this->db->delete('clients', 'id = ?', [$id]);
            $_SESSION['success_message'] = 'Клиент успешно удален!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Ошибка при удалении клиента: ' . $e->getMessage();
        }
    }
    
    private function validateClientData($data) {
        $errors = [];
        
        if (empty($data['full_name'])) {
            $errors[] = 'ФИО обязательно для заполнения';
        } elseif (strlen($data['full_name']) < 2) {
            $errors[] = 'ФИО должно содержать минимум 2 символа';
        }
        
        if (empty($data['phone'])) {
            $errors[] = 'Номер телефона обязателен для заполнения';
        } elseif (!preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $data['phone'])) {
            $errors[] = 'Неверный формат номера телефона';
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Неверный формат email адреса';
        }
        
        if (!empty($data['birth_date'])) {
            $birthDate = new DateTime($data['birth_date']);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
            
            if ($age < 18 || $age > 100) {
                $errors[] = 'Возраст должен быть от 18 до 100 лет';
            }
        }
        
        return $errors;
    }
    
    private function getClients($search = '') {
        $sql = "SELECT c.*, 
                       COUNT(DISTINCT v.id) as vehicles_count,
                       COUNT(DISTINCT o.id) as orders_count,
                       COALESCE(SUM(o.total_amount), 0) as total_spent
                FROM clients c
                LEFT JOIN vehicles v ON c.id = v.client_id
                LEFT JOIN orders o ON c.id = o.client_id
                GROUP BY c.id";
        
        $params = [];
        
        if (!empty($search)) {
            $sql = "SELECT c.*, 
                           COUNT(DISTINCT v.id) as vehicles_count,
                           COUNT(DISTINCT o.id) as orders_count,
                           COALESCE(SUM(o.total_amount), 0) as total_spent
                    FROM clients c
                    LEFT JOIN vehicles v ON c.id = v.client_id
                    LEFT JOIN orders o ON c.id = o.client_id
                    WHERE c.full_name LIKE ? OR c.phone LIKE ? OR c.email LIKE ?
                    GROUP BY c.id";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    private function getClientById($id) {
        $sql = "SELECT * FROM clients WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    private function getClientVehicles($clientId) {
        $sql = "SELECT * FROM vehicles WHERE client_id = ? ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$clientId]);
    }
    
    private function getClientOrders($clientId) {
        $sql = "SELECT o.*, v.brand, v.model, v.license_plate 
                FROM orders o
                LEFT JOIN vehicles v ON o.vehicle_id = v.id
                WHERE o.client_id = ? 
                ORDER BY o.created_at DESC";
        return $this->db->fetchAll($sql, [$clientId]);
    }
    
    private function renderView($view, $data = []) {
        // Убеждаемся, что переменная $page доступна
        global $page;
        if (!isset($page)) {
            $page = $_GET['page'] ?? 'clients';
        }
        
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>
