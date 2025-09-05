<?php
class CalculatorController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $services = $this->getServices();
        
        $this->renderView('calculator/index', [
            'services' => $services
        ]);
    }
    
    private function getServices() {
        return $this->db->fetchAll(
            "SELECT * FROM services WHERE active = 1 ORDER BY category, name"
        );
    }
    
    private function renderView($view, $data = []) {
        // Убеждаемся, что переменная $page доступна
        global $page;
        if (!isset($page)) {
            $page = $_GET['page'] ?? 'calculator';
        }
        
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>
