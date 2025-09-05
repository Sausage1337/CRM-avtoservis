<?php
class ProcessController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Главная страница процессов
        $stats = $this->getProcessStats();
        $activeProcesses = $this->getActiveProcesses();
        $recentProcesses = $this->getRecentProcesses();
        
        $this->renderView('process/index', [
            'stats' => $stats,
            'activeProcesses' => $activeProcesses,
            'recentProcesses' => $recentProcesses
        ]);
    }
    
    public function calculation() {
        // Страница калькуляции
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCalculationSubmit();
        }
        
        $services = $this->db->fetchAll("SELECT * FROM services ORDER BY name");
        $calculations = $this->db->fetchAll("
            SELECT sc.*, u.full_name as created_by_name 
            FROM service_calculations sc 
            LEFT JOIN users u ON sc.created_by = u.id 
            ORDER BY sc.created_at DESC 
            LIMIT 10
        ");
        
        $this->renderView('process/calculation', [
            'services' => $services,
            'calculations' => $calculations
        ]);
    }
    
    public function clientBooking() {
        // Страница записи клиента
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleClientBookingSubmit();
        }
        
        $clients = $this->db->fetchAll("SELECT * FROM clients ORDER BY full_name");
        $bookings = $this->db->fetchAll("
            SELECT cb.*, c.full_name, v.license_plate 
            FROM client_bookings cb 
            JOIN clients c ON cb.client_id = c.id 
            JOIN vehicles v ON cb.vehicle_id = v.id 
            WHERE cb.status IN ('pending', 'confirmed') 
            ORDER BY cb.booking_date, cb.booking_time 
            LIMIT 10
        ");
        $timeSlots = $this->getAvailableTimeSlots();
        
        $this->renderView('process/client_booking', [
            'clients' => $clients,
            'bookings' => $bookings,
            'timeSlots' => $timeSlots
        ]);
    }
    
    public function workOrder() {
        // Страница заказ-наряда
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleWorkOrderSubmit();
        }
        
        $calculations = $this->db->fetchAll("
            SELECT sc.*, u.full_name as created_by_name 
            FROM service_calculations sc 
            LEFT JOIN users u ON sc.created_by = u.id 
            WHERE sc.status = 'ready' 
            ORDER BY sc.created_at DESC
        ");
        
        $bookings = $this->db->fetchAll("
            SELECT cb.*, c.full_name, v.license_plate 
            FROM client_bookings cb 
            JOIN clients c ON cb.client_id = c.id 
            JOIN vehicles v ON cb.vehicle_id = v.id 
            WHERE cb.status = 'confirmed' 
            ORDER BY cb.booking_date, cb.booking_time
        ");
        
        $workOrders = $this->db->fetchAll("
            SELECT wo.*, c.full_name, v.license_plate 
            FROM work_orders wo 
            JOIN clients c ON wo.client_id = c.id 
            JOIN vehicles v ON wo.vehicle_id = v.id 
            ORDER BY wo.created_at DESC 
            LIMIT 10
        ");
        
        $this->renderView('process/work_order', [
            'calculations' => $calculations,
            'bookings' => $bookings,
            'workOrders' => $workOrders
        ]);
    }
    
    public function viewProcess() {
        // Просмотр конкретного процесса
        $processId = $_GET['id'] ?? null;
        if (!$processId) {
            header('Location: index.php?page=process');
            exit;
        }
        
        $process = $this->db->fetch("SELECT * FROM process_flow WHERE id = ?", [$processId]);
        if (!$process) {
            header('Location: index.php?page=process');
            exit;
        }
        
        // Получаем связанные данные
        $calculation = null;
        $booking = null;
        $workOrder = null;
        
        if ($process['calculation_id']) {
            $calculation = $this->db->fetch("SELECT * FROM service_calculations WHERE id = ?", [$process['calculation_id']]);
        }
        
        if ($process['booking_id']) {
            $booking = $this->db->fetch("
                SELECT cb.*, c.full_name, v.license_plate 
                FROM client_bookings cb 
                JOIN clients c ON cb.client_id = c.id 
                JOIN vehicles v ON cb.vehicle_id = v.id 
                WHERE cb.id = ?
            ", [$process['booking_id']]);
        }
        
        if ($process['work_order_id']) {
            $workOrder = $this->db->fetch("
                SELECT wo.*, c.full_name, v.license_plate 
                FROM work_orders wo 
                JOIN clients c ON wo.client_id = c.id 
                JOIN vehicles v ON wo.vehicle_id = v.id 
                WHERE wo.id = ?
            ", [$process['work_order_id']]);
        }
        
        $this->renderView('process/view_process', [
            'process' => $process,
            'calculation' => $calculation,
            'booking' => $booking,
            'workOrder' => $workOrder
        ]);
    }
    
    public function linkProcess() {
        // Связывание этапов процесса
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $calculationId = $_POST['calculation_id'] ?? null;
            $bookingId = $_POST['booking_id'] ?? null;
            $workOrderId = $_POST['work_order_id'] ?? null;
            
            if ($calculationId || $bookingId || $workOrderId) {
                $this->createOrUpdateProcess($calculationId, $bookingId, $workOrderId);
                header('Location: index.php?page=process');
                exit;
            }
        }
        
        // Показываем форму связывания
        $calculations = $this->db->fetchAll("SELECT * FROM service_calculations WHERE status = 'ready'");
        $bookings = $this->db->fetchAll("SELECT * FROM client_bookings WHERE status = 'confirmed'");
        $workOrders = $this->db->fetchAll("SELECT * FROM work_orders WHERE status != 'completed'");
        
        $this->renderView('process/link_process', [
            'calculations' => $calculations,
            'bookings' => $bookings,
            'workOrders' => $workOrders
        ]);
    }
    
    public function getClientInfo() {
        // AJAX endpoint для получения информации о клиенте
        $phone = $_GET['phone'] ?? '';
        if (!$phone) {
            echo json_encode(['success' => false, 'message' => 'Телефон не указан']);
            return;
        }
        
        $client = $this->db->fetch("SELECT * FROM clients WHERE phone = ?", [$phone]);
        if ($client) {
            echo json_encode(['success' => true, 'client' => $client]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Клиент не найден']);
        }
    }
    
    public function getVehicleInfo() {
        // AJAX endpoint для получения информации об автомобиле
        $licensePlate = $_GET['license_plate'] ?? '';
        if (!$licensePlate) {
            echo json_encode(['success' => false, 'message' => 'Госномер не указан']);
            return;
        }
        
        $vehicle = $this->db->fetch("
            SELECT v.*, c.full_name as client_name 
            FROM vehicles v 
            JOIN clients c ON v.client_id = c.id 
            WHERE v.license_plate = ?
        ", [$licensePlate]);
        
        if ($vehicle) {
            echo json_encode(['success' => true, 'vehicle' => $vehicle]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Автомобиль не найден']);
        }
    }
    
    public function getClientVehicles() {
        // AJAX endpoint для получения автомобилей клиента
        $clientId = $_GET['client_id'] ?? '';
        if (!$clientId) {
            echo json_encode(['success' => false, 'message' => 'ID клиента не указан']);
            return;
        }
        
        $vehicles = $this->db->fetchAll("
            SELECT id, brand, model, year, license_plate, vin 
            FROM vehicles 
            WHERE client_id = ?
            ORDER BY created_at DESC
        ", [$clientId]);
        
        if ($vehicles) {
            echo json_encode(['success' => true, 'vehicles' => $vehicles]);
        } else {
            echo json_encode(['success' => false, 'message' => 'У клиента нет автомобилей', 'vehicles' => []]);
        }
    }
    
    // Приватные методы для обработки форм
    private function handleCalculationSubmit() {
        $description = $_POST['description'] ?? '';
        $services = $_POST['services'] ?? [];
        $quantities = $_POST['quantities'] ?? [];
        $prices = $_POST['prices'] ?? [];
        
        if (empty($services)) {
            $_SESSION['error'] = 'Добавьте хотя бы одну услугу';
            return;
        }
        
        try {
            $this->db->beginTransaction();
            
            // Создаем калькуляцию
            $calculationId = $this->db->insert("
                INSERT INTO service_calculations (description, total_amount, status, created_by) 
                VALUES (?, 0, 'draft', ?)
            ", [$description, $_SESSION['user_id'] ?? 1]);
            
            $totalAmount = 0;
            
            // Добавляем элементы калькуляции
            foreach ($services as $index => $serviceId) {
                if ($serviceId && isset($quantities[$index]) && isset($prices[$index])) {
                    $quantity = (int)$quantities[$index];
                    $price = (float)$prices[$index];
                    $amount = $quantity * $price;
                    $totalAmount += $amount;
                    
                    $this->db->insert("
                        INSERT INTO calculation_items (calculation_id, service_id, quantity, price, amount) 
                        VALUES (?, ?, ?, ?, ?)
                    ", [$calculationId, $serviceId, $quantity, $price, $amount]);
                }
            }
            
            // Обновляем общую сумму
            $this->db->update("
                UPDATE service_calculations SET total_amount = ?, status = 'ready' WHERE id = ?
            ", [$totalAmount, $calculationId]);
            
            $this->db->commit();
            $_SESSION['success'] = 'Калькуляция успешно создана!';
            
        } catch (Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Ошибка при создании калькуляции: ' . $e->getMessage();
        }
    }
    
    private function handleClientBookingSubmit() {
        $clientId = $_POST['client_id'] ?? '';
        $vehicleId = $_POST['vehicle_id'] ?? '';
        $bookingDate = $_POST['booking_date'] ?? '';
        $bookingTime = $_POST['booking_time'] ?? '';
        $services = $_POST['services'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        if (!$clientId || !$vehicleId || !$bookingDate || !$bookingTime) {
            $_SESSION['error'] = 'Заполните все обязательные поля';
            return;
        }
        
        try {
            $bookingId = $this->db->insert("
                INSERT INTO client_bookings (client_id, vehicle_id, booking_date, booking_time, services, notes, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ", [$clientId, $vehicleId, $bookingDate, $bookingTime, $services, $notes, $_SESSION['user_id'] ?? 1]);
            
            $_SESSION['success'] = 'Запись клиента успешно создана!';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Ошибка при создании записи: ' . $e->getMessage();
        }
    }
    
    private function handleWorkOrderSubmit() {
        $calculationId = $_POST['calculation_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        $clientId = $_POST['client_id'] ?? '';
        $vehicleId = $_POST['vehicle_id'] ?? '';
        $priority = $_POST['priority'] ?? 'normal';
        $estimatedDuration = $_POST['estimated_duration'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        if (!$clientId || !$vehicleId) {
            $_SESSION['error'] = 'Выберите клиента и автомобиль';
            return;
        }
        
        try {
            $this->db->beginTransaction();
            
            // Создаем заказ-наряд
            $workOrderId = $this->db->insert("
                INSERT INTO work_orders (calculation_id, client_id, vehicle_id, priority, estimated_duration, notes, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ", [$calculationId, $clientId, $vehicleId, $priority, $estimatedDuration, $notes, $_SESSION['user_id'] ?? 1]);
            
            // Если есть калькуляция, обновляем её статус
            if ($calculationId) {
                $this->db->update("
                    UPDATE service_calculations SET status = 'used' WHERE id = ?
                ", [$calculationId]);
            }
            
            // Если есть запись, связываем её
            if ($bookingId) {
                $this->db->update("
                    UPDATE client_bookings SET work_order_id = ? WHERE id = ?
                ", [$workOrderId, $bookingId]);
            }
            
            // Создаем или обновляем процесс
            $this->createOrUpdateProcess($calculationId, $bookingId, $workOrderId);
            
            $this->db->commit();
            $_SESSION['success'] = 'Заказ-наряд успешно создан!';
            
        } catch (Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Ошибка при создании заказ-наряда: ' . $e->getMessage();
        }
    }
    
    private function createOrUpdateProcess($calculationId, $bookingId, $workOrderId) {
        // Ищем существующий процесс
        $existingProcess = $this->db->fetch("
            SELECT * FROM process_flow 
            WHERE calculation_id = ? OR booking_id = ? OR work_order_id = ?
        ", [$calculationId, $bookingId, $workOrderId]);
        
        if ($existingProcess) {
            // Обновляем существующий процесс
            $this->db->update("
                UPDATE process_flow 
                SET calculation_id = COALESCE(?, calculation_id),
                    booking_id = COALESCE(?, booking_id),
                    work_order_id = COALESCE(?, work_order_id),
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ", [$calculationId, $bookingId, $workOrderId, $existingProcess['id']]);
        } else {
            // Создаем новый процесс
            $this->db->insert("
                INSERT INTO process_flow (calculation_id, booking_id, work_order_id, created_by) 
                VALUES (?, ?, ?, ?)
            ", [$calculationId, $bookingId, $workOrderId, $_SESSION['user_id'] ?? 1]);
        }
    }
    
    // Вспомогательные методы
    private function getProcessStats() {
        $totalProcesses = $this->db->fetch("SELECT COUNT(*) as count FROM process_flow")['count'];
        $activeProcesses = $this->db->fetch("SELECT COUNT(*) as count FROM process_flow WHERE status = 'active'")['count'];
        $calculations = $this->db->fetch("SELECT COUNT(*) as count FROM service_calculations")['count'];
        $bookings = $this->db->fetch("SELECT COUNT(*) as count FROM client_bookings")['count'];
        $workOrders = $this->db->fetch("SELECT COUNT(*) as count FROM work_orders")['count'];
        $completed = $this->db->fetch("SELECT COUNT(*) as count FROM work_orders WHERE status = 'completed'")['count'];
        
        return [
            'total' => $totalProcesses,
            'active' => $activeProcesses,
            'calculations' => $calculations,
            'bookings' => $bookings,
            'workOrders' => $workOrders,
            'completed' => $completed
        ];
    }
    
    private function getActiveProcesses() {
        return $this->db->fetchAll("
            SELECT pf.*, 
                   sc.description as calculation_desc,
                   c.full_name as client_name,
                   v.license_plate
            FROM process_flow pf
            LEFT JOIN service_calculations sc ON pf.calculation_id = sc.id
            LEFT JOIN client_bookings cb ON pf.booking_id = cb.id
            LEFT JOIN clients c ON cb.client_id = c.id
            LEFT JOIN vehicles v ON cb.vehicle_id = v.id
            WHERE pf.status = 'active'
            ORDER BY pf.updated_at DESC
            LIMIT 5
        ");
    }
    
    private function getRecentProcesses() {
        return $this->db->fetchAll("
            SELECT pf.*, 
                   sc.description as calculation_desc,
                   c.full_name as client_name,
                   v.license_plate
            FROM process_flow pf
            LEFT JOIN service_calculations sc ON pf.calculation_id = sc.id
            LEFT JOIN client_bookings cb ON pf.booking_id = cb.id
            LEFT JOIN clients c ON cb.client_id = c.id
            LEFT JOIN vehicles v ON cb.vehicle_id = v.id
            ORDER BY pf.created_at DESC
            LIMIT 10
        ");
    }
    
    private function getAvailableTimeSlots() {
        // Генерируем временные слоты с 9:00 до 18:00
        $slots = [];
        for ($hour = 9; $hour <= 18; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
            $slots[] = sprintf('%02d:30', $hour);
        }
        return $slots;
    }
    
    private function renderView($view, $data = []) {
        global $page;
        if (!isset($page)) {
            $page = $_GET['page'] ?? 'process';
        }
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>
