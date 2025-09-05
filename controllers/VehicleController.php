<?php
class VehicleController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $search = $_GET['search'] ?? '';
        $vehicles = $this->getVehicles($search);
        
        $this->renderView('vehicles/index', [
            'vehicles' => $vehicles,
            'search' => $search
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'license_plate' => $_POST['license_plate'] ?? '',
                'vin' => !empty($_POST['vin']) ? $_POST['vin'] : null, // Если пустой, то NULL
                'brand' => $_POST['brand'] ?? '',
                'model' => $_POST['model'] ?? '',
                'year' => $_POST['year'] ?? '',
                'engine_volume' => $_POST['engine_volume'] ?? '',
                'fuel_type' => $_POST['fuel_type'] ?? '',
                'transmission' => $_POST['transmission'] ?? '',
                'color' => $_POST['color'] ?? '',
                'client_id' => $_POST['client_id'] ?? null
            ];
            
            // Валидация
            $errors = $this->validateVehicle($data);
            
            if (empty($errors)) {
                try {
                    $this->db->insert('vehicles', $data);
                    header('Location: index.php?page=vehicles&success=1');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Ошибка при создании автомобиля: ' . $e->getMessage();
                }
            }
        }
        
        $clients = $this->db->fetchAll("SELECT id, full_name FROM clients ORDER BY full_name");
        
        $this->renderView('vehicles/create', [
            'errors' => $errors ?? [],
            'clients' => $clients,
            'formData' => $_POST ?? []
        ]);
    }
    
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=vehicles');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'license_plate' => $_POST['license_plate'] ?? '',
                'vin' => !empty($_POST['vin']) ? $_POST['vin'] : null, // Если пустой, то NULL
                'brand' => $_POST['brand'] ?? '',
                'model' => $_POST['model'] ?? '',
                'year' => $_POST['year'] ?? '',
                'engine_volume' => $_POST['engine_volume'] ?? '',
                'fuel_type' => $_POST['fuel_type'] ?? '',
                'transmission' => $_POST['transmission'] ?? '',
                'color' => $_POST['color'] ?? '',
                'client_id' => $_POST['client_id'] ?? null
            ];
            
            $errors = $this->validateVehicle($data, $id);
            
            if (empty($errors)) {
                try {
                    $this->db->update('vehicles', $data, 'id = ?', [$id]);
                    header('Location: index.php?page=vehicles&success=2');
                    exit();
                } catch (Exception $e) {
                    $errors[] = 'Ошибка при обновлении автомобиля: ' . $e->getMessage();
                }
            }
        }
        
        $vehicle = $this->db->fetch("SELECT * FROM vehicles WHERE id = ?", [$id]);
        if (!$vehicle) {
            header('Location: index.php?page=vehicles');
            exit();
        }
        
        $clients = $this->db->fetchAll("SELECT id, full_name FROM clients ORDER BY full_name");
        
        $this->renderView('vehicles/edit', [
            'vehicle' => $vehicle,
            'clients' => $clients,
            'errors' => $errors ?? [],
            'formData' => $_POST ?: $vehicle
        ]);
    }
    
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->db->delete('vehicles', 'id = ?', [$id]);
                header('Location: index.php?page=vehicles&success=3');
                exit();
            } catch (Exception $e) {
                header('Location: index.php?page=vehicles&error=delete');
                exit();
            }
        }
        
        header('Location: index.php?page=vehicles');
        exit();
    }
    
    public function getVehicleInfo() {
        $licensePlate = $_GET['license_plate'] ?? '';
        
        if (empty($licensePlate)) {
            http_response_code(400);
            echo json_encode(['error' => 'Номер не указан']);
            return;
        }
        
        // Здесь будет API запрос для получения информации об автомобиле
        // Пока возвращаем заглушку
        $vehicleInfo = $this->getVehicleFromAPI($licensePlate);
        
        header('Content-Type: application/json');
        echo json_encode($vehicleInfo);
    }
    
    private function getVehicles($search = '') {
        $sql = "SELECT v.*, c.full_name as client_name 
                FROM vehicles v 
                LEFT JOIN clients c ON v.client_id = c.id";
        
        $params = [];
        if (!empty($search)) {
            $sql .= " WHERE v.license_plate LIKE ? OR v.brand LIKE ? OR v.model LIKE ? OR c.full_name LIKE ?";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        
        $sql .= " ORDER BY v.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    private function validateVehicle($data, $excludeId = null) {
        $errors = [];
        
        if (empty($data['license_plate'])) {
            $errors[] = 'Госномер обязателен';
        }
        
        if (empty($data['brand'])) {
            $errors[] = 'Марка автомобиля обязательна';
        }
        
        if (empty($data['model'])) {
            $errors[] = 'Модель автомобиля обязательна';
        }
        
        // Проверка уникальности госномера
        if (!empty($data['license_plate'])) {
            $sql = "SELECT id FROM vehicles WHERE license_plate = ?";
            $params = [$data['license_plate']];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $existing = $this->db->fetch($sql, $params);
            if ($existing) {
                $errors[] = 'Автомобиль с таким госномером уже существует';
            }
        }
        
        // Проверка уникальности VIN (только если он не пустой)
        if (!empty($data['vin'])) {
            $sql = "SELECT id FROM vehicles WHERE vin = ?";
            $params = [$data['vin']];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $existing = $this->db->fetch($sql, $params);
            if ($existing) {
                $errors[] = 'Автомобиль с таким VIN уже существует';
            }
            
            // Проверка формата VIN (17 символов)
            if (strlen($data['vin']) !== 17) {
                $errors[] = 'VIN должен содержать 17 символов';
            }
        }
        
        return $errors;
    }
    
    private function getVehicleFromAPI($licensePlate) {
        // Заглушка для API запроса
        // В реальном проекте здесь будет запрос к внешнему API
        return [
            'success' => true,
            'data' => [
                'brand' => 'Toyota',
                'model' => 'Camry',
                'year' => '2020',
                'vin' => '1HGBH41JXMN109186',
                'engine_volume' => '2.5',
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'color' => 'Белый'
            ]
        ];
    }
    
    private function renderView($view, $data = []) {
        // Убеждаемся, что переменная $page доступна
        global $page;
        if (!isset($page)) {
            $page = $_GET['page'] ?? 'vehicles';
        }
        
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>
