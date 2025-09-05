<?php
class ReportController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $reportType = $_GET['type'] ?? 'general';
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $data = $this->getReportData($reportType, $startDate, $endDate);
        
        $this->renderView('reports/index', [
            'reportType' => $reportType,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'data' => $data
        ]);
    }
    
    private function getReportData($type, $startDate, $endDate) {
        switch ($type) {
            case 'sales':
                return $this->getSalesReport($startDate, $endDate);
            case 'services':
                return $this->getServicesReport($startDate, $endDate);
            case 'clients':
                return $this->getClientsReport($startDate, $endDate);
            default:
                return $this->getGeneralReport($startDate, $endDate);
        }
    }
    
    private function getGeneralReport($startDate, $endDate) {
        return [
            'totalOrders' => $this->db->fetch(
                "SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) BETWEEN ? AND ?",
                [$startDate, $endDate]
            )['count'],
            'totalRevenue' => $this->db->fetch(
                "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE DATE(created_at) BETWEEN ? AND ? AND status = 'completed'",
                [$startDate, $endDate]
            )['total']
        ];
    }
    
    private function getSalesReport($startDate, $endDate) {
        return $this->db->fetchAll(
            "SELECT DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue 
             FROM orders 
             WHERE DATE(created_at) BETWEEN ? AND ? AND status = 'completed'
             GROUP BY DATE(created_at)
             ORDER BY date",
            [$startDate, $endDate]
        );
    }
    
    private function getServicesReport($startDate, $endDate) {
        return $this->db->fetchAll(
            "SELECT s.name, COUNT(oi.id) as count, SUM(oi.total_price) as revenue
             FROM order_items oi
             JOIN services s ON oi.service_id = s.id
             JOIN orders o ON oi.order_id = o.id
             WHERE DATE(o.created_at) BETWEEN ? AND ? AND o.status = 'completed'
             GROUP BY s.id
             ORDER BY revenue DESC",
            [$startDate, $endDate]
        );
    }
    
    private function getClientsReport($startDate, $endDate) {
        return $this->db->fetchAll(
            "SELECT c.full_name, COUNT(o.id) as orders, SUM(o.total_amount) as total_spent
             FROM orders o
             JOIN clients c ON o.client_id = c.id
             WHERE DATE(o.created_at) BETWEEN ? AND ? AND o.status = 'completed'
             GROUP BY c.id
             ORDER BY total_spent DESC
             LIMIT 20",
            [$startDate, $endDate]
        );
    }
    
    private function renderView($view, $data = []) {
        // Убеждаемся, что переменная $page доступна
        global $page;
        if (!isset($page)) {
            $page = $_GET['page'] ?? 'reports';
        }
        
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>
