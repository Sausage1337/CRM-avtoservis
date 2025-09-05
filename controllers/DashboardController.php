<?php
class DashboardController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Получение статистики
        $stats = $this->getDashboardStats();
        
        // Получение последних заказов
        $recentOrders = $this->getRecentOrders();
        
        // Получение активных заказов
        $activeOrders = $this->getActiveOrders();
        
        $this->renderView('dashboard/index', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'activeOrders' => $activeOrders
        ]);
    }
    
    private function getDashboardStats() {
        $stats = [];
        
        // Общее количество клиентов
        $stats['totalClients'] = $this->db->fetch("SELECT COUNT(*) as count FROM clients")['count'];
        
        // Общее количество автомобилей
        $stats['totalVehicles'] = $this->db->fetch("SELECT COUNT(*) as count FROM vehicles")['count'];
        
        // Общее количество заказов
        $stats['totalOrders'] = $this->db->fetch("SELECT COUNT(*) as count FROM orders")['count'];
        
        // Заказы за сегодня
        $stats['todayOrders'] = $this->db->fetch(
            "SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = CURDATE()"
        )['count'];
        
        // Заказы в работе
        $stats['inProgressOrders'] = $this->db->fetch(
            "SELECT COUNT(*) as count FROM orders WHERE status = 'in_progress'"
        )['count'];
        
        // Общая выручка за сегодня
        $stats['todayRevenue'] = $this->db->fetch(
            "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE DATE(created_at) = CURDATE() AND status = 'completed'"
        )['total'];
        
        // Общая выручка за месяц
        $stats['monthRevenue'] = $this->db->fetch(
            "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND status = 'completed'"
        )['total'];
        
        return $stats;
    }
    
    private function getRecentOrders() {
        return $this->db->fetchAll(
            "SELECT o.*, c.full_name as client_name, v.license_plate, v.brand, v.model 
             FROM orders o 
             JOIN clients c ON o.client_id = c.id 
             JOIN vehicles v ON o.vehicle_id = v.id 
             ORDER BY o.created_at DESC 
             LIMIT 10"
        );
    }
    
    private function getActiveOrders() {
        return $this->db->fetchAll(
            "SELECT o.*, c.full_name as client_name, v.license_plate, v.brand, v.model 
             FROM orders o 
             JOIN clients c ON o.client_id = c.id 
             JOIN vehicles v ON o.vehicle_id = v.id 
             WHERE o.status IN ('new', 'in_progress') 
             ORDER BY o.created_at ASC 
             LIMIT 5"
        );
    }
    
    private function renderView($view, $data = []) {
        // Убеждаемся, что переменная $page доступна
        global $page;
        if (!isset($page)) {
            $page = $_GET['page'] ?? 'dashboard';
        }
        
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>
