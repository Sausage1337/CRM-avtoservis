<?php
require_once 'config/database.php';

class ServiceController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        try {
            $services = $this->db->fetchAll("
                SELECT * FROM services 
                ORDER BY category, name
            ");
            
            $categories = $this->db->fetchAll("
                SELECT DISTINCT category 
                FROM services 
                WHERE category IS NOT NULL 
                ORDER BY category
            ");
            
            $content = 'views/services/index.php';
            include 'views/layout.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Ошибка при загрузке услуг: ' . $e->getMessage();
            header('Location: index.php?page=dashboard');
            exit();
        }
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $price = floatval($_POST['price'] ?? 0);
                $duration = intval($_POST['duration'] ?? 60);
                $category = trim($_POST['category'] ?? '');
                $active = isset($_POST['active']) ? 1 : 0;
                
                if (empty($name)) {
                    throw new Exception('Название услуги обязательно');
                }
                
                if ($price <= 0) {
                    throw new Exception('Цена должна быть больше 0');
                }
                
                $this->db->insert('services', [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'duration' => $duration,
                    'category' => $category,
                    'active' => $active
                ]);
                
                $_SESSION['success'] = 'Услуга успешно создана';
                header('Location: index.php?page=services');
                exit();
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        $categories = $this->db->fetchAll("
            SELECT DISTINCT category 
            FROM services 
            WHERE category IS NOT NULL 
            ORDER BY category
        ");
        
        $content = 'views/services/create.php';
        include 'views/layout.php';
    }
    
    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $price = floatval($_POST['price'] ?? 0);
                $duration = intval($_POST['duration'] ?? 60);
                $category = trim($_POST['category'] ?? '');
                $active = isset($_POST['active']) ? 1 : 0;
                
                if (empty($name)) {
                    throw new Exception('Название услуги обязательно');
                }
                
                if ($price <= 0) {
                    throw new Exception('Цена должна быть больше 0');
                }
                
                $this->db->update('services', [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'duration' => $duration,
                    'category' => $category,
                    'active' => $active
                ], 'id = ?', [$id]);
                
                $_SESSION['success'] = 'Услуга успешно обновлена';
                header('Location: index.php?page=services');
                exit();
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        $service = $this->db->fetch("SELECT * FROM services WHERE id = ?", [$id]);
        
        if (!$service) {
            $_SESSION['error'] = 'Услуга не найдена';
            header('Location: index.php?page=services');
            exit();
        }
        
        $categories = $this->db->fetchAll("
            SELECT DISTINCT category 
            FROM services 
            WHERE category IS NOT NULL 
            ORDER BY category
        ");
        
        $content = 'views/services/edit.php';
        include 'views/layout.php';
    }
    
    public function delete() {
        $id = intval($_GET['id'] ?? 0);
        
        try {
            // Проверяем, используется ли услуга в заказах
            $orderItems = $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM order_items 
                WHERE service_id = ?
            ", [$id]);
            
            if ($orderItems['count'] > 0) {
                // Если услуга используется, просто деактивируем её
                $this->db->update('services', ['active' => 0], 'id = ?', [$id]);
                $_SESSION['success'] = 'Услуга деактивирована (используется в заказах)';
            } else {
                // Если не используется, можно удалить
                $this->db->delete('services', 'id = ?', [$id]);
                $_SESSION['success'] = 'Услуга успешно удалена';
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Ошибка при удалении услуги: ' . $e->getMessage();
        }
        
        header('Location: index.php?page=services');
        exit();
    }
    
    public function toggle() {
        $id = intval($_GET['id'] ?? 0);
        
        try {
            $service = $this->db->fetch("SELECT active FROM services WHERE id = ?", [$id]);
            
            if (!$service) {
                throw new Exception('Услуга не найдена');
            }
            
            $newStatus = $service['active'] ? 0 : 1;
            $this->db->update('services', ['active' => $newStatus], 'id = ?', [$id]);
            
            $status = $newStatus ? 'активирована' : 'деактивирована';
            $_SESSION['success'] = "Услуга $status";
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Ошибка: ' . $e->getMessage();
        }
        
        header('Location: index.php?page=services');
        exit();
    }
}
?>
