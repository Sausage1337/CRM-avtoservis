<?php
require_once 'config/database.php';

class ContactController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Получаем контактную информацию из настроек
        $contactInfo = $this->getContactInfo();
        
        // Получаем последние заявки на звонок (для админов)
        $callbacks = [];
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
            $callbacks = $this->db->fetchAll("
                SELECT * FROM callbacks 
                WHERE status = 'new' 
                ORDER BY created_at DESC 
                LIMIT 10
            ");
        }
        
        $content = 'views/contact/index.php';
        include 'views/layout.php';
    }
    
    public function callback() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $name = trim($_POST['name'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                $message = trim($_POST['message'] ?? '');
                $best_time = $_POST['best_time'] ?? '';
                
                if (empty($name) || empty($phone)) {
                    throw new Exception('Имя и телефон обязательны для заполнения');
                }
                
                // Проверяем формат телефона (базовая проверка)
                if (!preg_match('/^[\+]?[0-9\-\(\)\s]+$/', $phone)) {
                    throw new Exception('Неверный формат телефона');
                }
                
                $this->db->insert('callbacks', [
                    'name' => $name,
                    'phone' => $phone,
                    'message' => $message,
                    'best_time' => $best_time,
                    'status' => 'new',
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
                ]);
                
                // Возвращаем JSON ответ для AJAX
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Заявка отправлена! Мы свяжемся с вами в ближайшее время.']);
                    exit();
                }
                
                $_SESSION['success'] = 'Заявка отправлена! Мы свяжемся с вами в ближайшее время.';
                header('Location: index.php?page=contact');
                exit();
                
            } catch (Exception $e) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit();
                }
                
                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?page=contact');
                exit();
            }
        }
    }
    
    public function chat() {
        // Обработка сообщений чата
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $message = trim($_POST['message'] ?? '');
                $session_id = $_POST['session_id'] ?? session_id();
                
                if (empty($message)) {
                    throw new Exception('Сообщение не может быть пустым');
                }
                
                $this->db->insert('chat_messages', [
                    'session_id' => $session_id,
                    'message' => $message,
                    'sender_type' => 'client',
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
                ]);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Сообщение отправлено',
                    'auto_response' => $this->getAutoResponse($message)
                ]);
                exit();
                
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit();
            }
        }
    }
    
    public function getChatMessages() {
        $session_id = $_GET['session_id'] ?? session_id();
        
        $messages = $this->db->fetchAll("
            SELECT * FROM chat_messages 
            WHERE session_id = ? 
            ORDER BY created_at ASC
        ", [$session_id]);
        
        header('Content-Type: application/json');
        echo json_encode(['messages' => $messages]);
        exit();
    }
    
    private function getContactInfo() {
        return [
            'phone' => '+7 (999) 123-45-67',
            'email' => 'info@autoservice.ru',
            'address' => 'г. Москва, ул. Автомобильная, д. 123',
            'coordinates' => [55.751244, 37.618423], // Москва
            'hours' => [
                'mon_fri' => '08:00 - 20:00',
                'saturday' => '09:00 - 18:00',
                'sunday' => '10:00 - 16:00'
            ],
            'services_24h' => false,
            'emergency_phone' => '+7 (999) 123-45-68'
        ];
    }
    
    private function getAutoResponse($message) {
        $message = mb_strtolower($message);
        
        // Простые автоответы
        if (strpos($message, 'цена') !== false || strpos($message, 'стоимость') !== false) {
            return 'Стоимость услуг зависит от вида работ. Позвоните нам по телефону +7 (999) 123-45-67 для получения точной стоимости.';
        }
        
        if (strpos($message, 'время') !== false || strpos($message, 'график') !== false) {
            return 'Мы работаем: Пн-Пт 08:00-20:00, Сб 09:00-18:00, Вс 10:00-16:00';
        }
        
        if (strpos($message, 'адрес') !== false || strpos($message, 'где') !== false) {
            return 'Наш адрес: г. Москва, ул. Автомобильная, д. 123. Удобная парковка для клиентов.';
        }
        
        if (strpos($message, 'запись') !== false || strpos($message, 'записаться') !== false) {
            return 'Для записи на сервис позвоните по телефону +7 (999) 123-45-67 или оставьте заявку на обратный звонок.';
        }
        
        return 'Спасибо за ваше сообщение! Наш оператор ответит вам в ближайшее время. Для срочных вопросов звоните: +7 (999) 123-45-67';
    }
}
?>
