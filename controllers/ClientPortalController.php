<?php
class ClientPortalController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Проверяем авторизацию клиента
        if (!isset($_SESSION['client_id'])) {
            header('Location: index.php?page=client_login');
            exit();
        }
        
        $clientId = $_SESSION['client_id'];
        $client = $this->db->fetch("SELECT * FROM clients WHERE id = ?", [$clientId]);
        
        // Получаем статистику клиента
        $stats = $this->getClientStats($clientId);
        
        // Получаем последние заказы
        $recentOrders = $this->getRecentOrders($clientId, 5);
        
        // Получаем активные заказы
        $activeOrders = $this->getActiveOrders($clientId);
        
        // Получаем напоминания о ТО
        $maintenanceReminders = $this->getMaintenanceReminders($clientId);
        
        $this->renderView('client_portal/index', [
            'client' => $client,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'activeOrders' => $activeOrders,
            'maintenanceReminders' => $maintenanceReminders
        ]);
    }
    
    public function orders() {
        if (!isset($_SESSION['client_id'])) {
            header('Location: index.php?page=client_login');
            exit();
        }
        
        $clientId = $_SESSION['client_id'];
        $status = $_GET['status'] ?? '';
        $orders = $this->getClientOrders($clientId, $status);
        
        $this->renderView('client_portal/orders', [
            'orders' => $orders,
            'status' => $status
        ]);
    }
    
    public function orderDetails() {
        if (!isset($_SESSION['client_id'])) {
            header('Location: index.php?page=client_login');
            exit();
        }
        
        $orderId = $_GET['id'] ?? null;
        $clientId = $_SESSION['client_id'];
        
        if (!$orderId) {
            header('Location: index.php?page=client_portal&action=orders');
            exit();
        }
        
        // Получаем детали заказа с проверкой принадлежности клиенту
        $order = $this->db->fetch(
            "SELECT o.*, c.full_name as client_name, v.license_plate, v.brand, v.model 
             FROM orders o 
             JOIN clients c ON o.client_id = c.id 
             JOIN vehicles v ON o.vehicle_id = v.id 
             WHERE o.id = ? AND o.client_id = ?",
            [$orderId, $clientId]
        );
        
        if (!$order) {
            header('Location: index.php?page=client_portal&action=orders');
            exit();
        }
        
        // Получаем элементы заказа
        try {
            // Проверяем какие колонки существуют в таблице services
            $columns = $this->db->fetchAll("DESCRIBE services");
            $columnNames = array_column($columns, 'Field');
            
            $selectFields = "oi.*, s.name as service_name";
            if (in_array('description', $columnNames)) {
                $selectFields .= ", s.description as service_description";
            } else {
                $selectFields .= ", '' as service_description";
            }
            
            $orderItems = $this->db->fetchAll(
                "SELECT {$selectFields}
                 FROM order_items oi 
                 JOIN services s ON oi.service_id = s.id 
                 WHERE oi.order_id = ?",
                [$orderId]
            );
        } catch (Exception $e) {
            $orderItems = [];
        }
        
        // Получаем историю статусов
        $statusHistory = $this->getOrderStatusHistory($orderId);
        
        // Получаем фото/видео отчеты
        $reports = $this->getOrderReports($orderId);
        
        $this->renderView('client_portal/order_details', [
            'order' => $order,
            'orderItems' => $orderItems,
            'statusHistory' => $statusHistory,
            'reports' => $reports
        ]);
    }
    
    public function vehicles() {
        if (!isset($_SESSION['client_id'])) {
            header('Location: index.php?page=client_login');
            exit();
        }
        
        $clientId = $_SESSION['client_id'];
        $vehicles = $this->db->fetchAll(
            "SELECT v.*, 
                    (SELECT MAX(service_date) FROM service_history WHERE vehicle_id = v.id) as last_service_date,
                    (SELECT MAX(mileage) FROM service_history WHERE vehicle_id = v.id) as last_mileage
             FROM vehicles v 
             WHERE v.client_id = ? 
             ORDER BY v.created_at DESC",
            [$clientId]
        );
        
        $this->renderView('client_portal/vehicles', [
            'vehicles' => $vehicles
        ]);
    }
    
    public function serviceBook() {
        if (!isset($_SESSION['client_id'])) {
            header('Location: index.php?page=client_login');
            exit();
        }
        
        $vehicleId = $_GET['vehicle_id'] ?? null;
        $clientId = $_SESSION['client_id'];
        
        if (!$vehicleId) {
            header('Location: index.php?page=client_portal&action=vehicles');
            exit();
        }
        
        // Проверяем принадлежность автомобиля клиенту
        $vehicle = $this->db->fetch(
            "SELECT * FROM vehicles WHERE id = ? AND client_id = ?",
            [$vehicleId, $clientId]
        );
        
        if (!$vehicle) {
            header('Location: index.php?page=client_portal&action=vehicles');
            exit();
        }
        
        // Получаем историю обслуживания
        $serviceHistory = $this->db->fetchAll(
            "SELECT * FROM service_history WHERE vehicle_id = ? ORDER BY service_date DESC",
            [$vehicleId]
        );
        
        // Получаем заказы для этого автомобиля
        $orders = $this->db->fetchAll(
            "SELECT o.*, 
                    (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as services_count
             FROM orders o 
             WHERE o.vehicle_id = ? AND o.client_id = ? 
             ORDER BY o.created_at DESC",
            [$vehicleId, $clientId]
        );
        
        $this->renderView('client_portal/service_book', [
            'vehicle' => $vehicle,
            'serviceHistory' => $serviceHistory,
            'orders' => $orders
        ]);
    }
    
    public function chat() {
        if (!isset($_SESSION['client_id'])) {
            header('Location: index.php?page=client_login');
            exit();
        }
        
        $clientId = $_SESSION['client_id'];
        $orderId = $_GET['order_id'] ?? null;
        
        // Получаем сообщения чата
        $messages = $this->getChatMessages($clientId, $orderId);
        
        $this->renderView('client_portal/chat', [
            'messages' => $messages,
            'orderId' => $orderId
        ]);
    }
    
    public function sendMessage() {
        if (!isset($_SESSION['client_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['error' => 'Неверный запрос']);
            return;
        }
        
        $clientId = $_SESSION['client_id'];
        $orderId = $_POST['order_id'] ?? null;
        $message = $_POST['message'] ?? '';
        
        if (empty($message)) {
            http_response_code(400);
            echo json_encode(['error' => 'Сообщение не может быть пустым']);
            return;
        }
        
        try {
            $this->db->insert('chat_messages', [
                'client_id' => $clientId,
                'order_id' => $orderId,
                'message' => $message,
                'is_from_client' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Ошибка при отправке сообщения']);
        }
    }
    
    public function loyalty() {
        if (!isset($_SESSION['client_id'])) {
            header('Location: index.php?page=client_login');
            exit();
        }
        
        $clientId = $_SESSION['client_id'];
        
        // Получаем информацию о лояльности
        $loyaltyInfo = $this->getLoyaltyInfo($clientId);
        
        // Получаем доступные скидки и бонусы
        $availableDiscounts = $this->getAvailableDiscounts($clientId);
        
        $this->renderView('client_portal/loyalty', [
            'loyaltyInfo' => $loyaltyInfo,
            'availableDiscounts' => $availableDiscounts
        ]);
    }
    
    public function payment() {
        if (!isset($_SESSION['client_id'])) {
            header('Location: index.php?page=client_login');
            exit();
        }
        
        $orderId = $_GET['order_id'] ?? null;
        $clientId = $_SESSION['client_id'];
        
        if (!$orderId) {
            header('Location: index.php?page=client_portal&action=orders');
            exit();
        }
        
        // Проверяем принадлежность заказа клиенту
        $order = $this->db->fetch(
            "SELECT * FROM orders WHERE id = ? AND client_id = ?",
            [$orderId, $clientId]
        );
        
        if (!$order) {
            header('Location: index.php?page=client_portal&action=orders');
            exit();
        }
        
        $this->renderView('client_portal/payment', [
            'order' => $order
        ]);
    }
    
    // Приватные методы для получения данных
    private function getClientStats($clientId) {
        $stats = [];
        
        // Общее количество заказов
        $stats['totalOrders'] = $this->db->fetch(
            "SELECT COUNT(*) as count FROM orders WHERE client_id = ?",
            [$clientId]
        )['count'];
        
        // Общая сумма потраченная
        $stats['totalSpent'] = $this->db->fetch(
            "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE client_id = ? AND status = 'completed'",
            [$clientId]
        )['total'];
        
        // Количество автомобилей
        $stats['totalVehicles'] = $this->db->fetch(
            "SELECT COUNT(*) as count FROM vehicles WHERE client_id = ?",
            [$clientId]
        )['count'];
        
        // Заказы в работе
        $stats['activeOrders'] = $this->db->fetch(
            "SELECT COUNT(*) as count FROM orders WHERE client_id = ? AND status IN ('new', 'in_progress')",
            [$clientId]
        )['count'];
        
        return $stats;
    }
    
    private function getRecentOrders($clientId, $limit = 5) {
        return $this->db->fetchAll(
            "SELECT o.*, v.license_plate, v.brand, v.model 
             FROM orders o 
             JOIN vehicles v ON o.vehicle_id = v.id 
             WHERE o.client_id = ? 
             ORDER BY o.created_at DESC 
             LIMIT ?",
            [$clientId, $limit]
        );
    }
    
    private function getActiveOrders($clientId) {
        return $this->db->fetchAll(
            "SELECT o.*, v.license_plate, v.brand, v.model 
             FROM orders o 
             JOIN vehicles v ON o.vehicle_id = v.id 
             WHERE o.client_id = ? AND o.status IN ('new', 'in_progress') 
             ORDER BY o.created_at ASC",
            [$clientId]
        );
    }
    
    private function getClientOrders($clientId, $status = '') {
        $sql = "SELECT o.*, v.license_plate, v.brand, v.model 
                FROM orders o 
                JOIN vehicles v ON o.vehicle_id = v.id 
                WHERE o.client_id = ?";
        
        $params = [$clientId];
        
        if (!empty($status)) {
            $sql .= " AND o.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    private function getMaintenanceReminders($clientId) {
        return $this->db->fetchAll(
            "SELECT sh.*, v.license_plate, v.brand, v.model 
             FROM service_history sh 
             JOIN vehicles v ON sh.vehicle_id = v.id 
             WHERE v.client_id = ? AND sh.next_service_date IS NOT NULL 
             AND sh.next_service_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
             ORDER BY sh.next_service_date ASC",
            [$clientId]
        );
    }
    
    private function getOrderStatusHistory($orderId) {
        return $this->db->fetchAll(
            "SELECT * FROM order_status_history WHERE order_id = ? ORDER BY created_at ASC",
            [$orderId]
        );
    }
    
    private function getOrderReports($orderId) {
        return $this->db->fetchAll(
            "SELECT * FROM order_reports WHERE order_id = ? ORDER BY created_at DESC",
            [$orderId]
        );
    }
    
    private function getChatMessages($clientId, $orderId = null) {
        $sql = "SELECT cm.*, c.full_name as client_name, u.full_name as staff_name 
                FROM chat_messages cm 
                LEFT JOIN clients c ON cm.client_id = c.id 
                LEFT JOIN users u ON cm.user_id = u.id 
                WHERE cm.client_id = ?";
        
        $params = [$clientId];
        
        if ($orderId) {
            $sql .= " AND cm.order_id = ?";
            $params[] = $orderId;
        }
        
        $sql .= " ORDER BY cm.created_at ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    private function getLoyaltyInfo($clientId) {
        // Получаем текущий уровень лояльности
        $totalSpent = $this->db->fetch(
            "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE client_id = ? AND status = 'completed'",
            [$clientId]
        )['total'];
        
        // Определяем уровень лояльности
        if ($totalSpent >= 100000) {
            $level = 'VIP';
            $discount = 15;
        } elseif ($totalSpent >= 50000) {
            $level = 'Gold';
            $discount = 10;
        } elseif ($totalSpent >= 20000) {
            $level = 'Silver';
            $discount = 5;
        } else {
            $level = 'Bronze';
            $discount = 0;
        }
        
        return [
            'level' => $level,
            'discount' => $discount,
            'totalSpent' => $totalSpent,
            'nextLevel' => $this->getNextLevel($totalSpent)
        ];
    }
    
    private function getNextLevel($totalSpent) {
        if ($totalSpent < 20000) {
            return ['level' => 'Silver', 'required' => 20000 - $totalSpent];
        } elseif ($totalSpent < 50000) {
            return ['level' => 'Gold', 'required' => 50000 - $totalSpent];
        } elseif ($totalSpent < 100000) {
            return ['level' => 'VIP', 'required' => 100000 - $totalSpent];
        } else {
            return null; // Максимальный уровень достигнут
        }
    }
    
    private function getAvailableDiscounts($clientId) {
        $loyaltyInfo = $this->getLoyaltyInfo($clientId);
        $discounts = [];
        
        if ($loyaltyInfo['discount'] > 0) {
            $discounts[] = [
                'type' => 'loyalty',
                'name' => 'Скидка по программе лояльности',
                'value' => $loyaltyInfo['discount'] . '%',
                'description' => 'Действует на все услуги'
            ];
        }
        
        // Здесь можно добавить другие типы скидок
        // Например, сезонные акции, купоны и т.д.
        
        return $discounts;
    }
    
    private function renderView($view, $data = []) {
        // Убеждаемся, что переменная $page доступна
        global $page;
        if (!isset($page)) {
            $page = $_GET['page'] ?? 'client_portal';
        }
        
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/client_layout.php";
    }
}
?>
