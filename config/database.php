<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    }
    
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    // Дополнительно: совместимые утилиты
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        if (count($row) === 1) {
            return array_values($row)[0];
        }
        return $row;
    }
    
    public function execute($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $command = strtoupper(strtok(trim($sql), " \t\n\r"));
        if ($command === 'INSERT') {
            return $this->connection->lastInsertId();
        }
        return $stmt->rowCount();
    }
    
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
        return $this->connection->commit();
    }
    
    public function rollback() {
        return $this->connection->rollBack();
    }
    
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        
        return $this->connection->lastInsertId();
    }
    
    public function update($table, $data, $where, $whereParams = []) {
        $set = [];
        $params = [];
        $i = 1;
        
        // Используем позиционные параметры для SET
        foreach ($data as $column => $value) {
            $set[] = "{$column} = ?";
            $params[] = $value;
            $i++;
        }
        $setClause = implode(', ', $set);
        
        // Добавляем параметры WHERE
        $params = array_merge($params, $whereParams);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        
        return $this->query($sql, $params)->rowCount();
    }
    
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $params)->rowCount();
    }
}

// Создание таблиц при первом запуске (можно отключить через константу RUN_INSTALL)
function createTables() {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    $tables = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100),
            role ENUM('admin', 'manager', 'mechanic') DEFAULT 'manager',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS clients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            email VARCHAR(100),
            address TEXT,
            birth_date DATE,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS vehicles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            license_plate VARCHAR(20) UNIQUE NOT NULL,
            vin VARCHAR(17) UNIQUE,
            brand VARCHAR(50) NOT NULL,
            model VARCHAR(50) NOT NULL,
            year INT,
            engine_volume DECIMAL(3,1),
            fuel_type ENUM('petrol', 'diesel', 'hybrid', 'electric'),
            transmission ENUM('manual', 'automatic'),
            color VARCHAR(30),
            client_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            duration INT DEFAULT 60, -- в минутах
            category VARCHAR(50),
            active BOOLEAN DEFAULT TRUE
        )",
        
        "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_number VARCHAR(20) UNIQUE NOT NULL,
            client_id INT NOT NULL,
            vehicle_id INT NOT NULL,
            status ENUM('new', 'in_progress', 'completed', 'cancelled') DEFAULT 'new',
            total_amount DECIMAL(10,2) DEFAULT 0,
            discount DECIMAL(5,2) DEFAULT 0,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            completed_at TIMESTAMP NULL,
            FOREIGN KEY (client_id) REFERENCES clients(id),
            FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
        )",
        
        "CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            service_id INT NOT NULL,
            quantity INT DEFAULT 1,
            unit_price DECIMAL(10,2) NOT NULL,
            total_price DECIMAL(10,2) NOT NULL,
            notes TEXT,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (service_id) REFERENCES services(id)
        )",
        
        "CREATE TABLE IF NOT EXISTS parts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            part_number VARCHAR(50),
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            stock_quantity INT DEFAULT 0,
            min_stock INT DEFAULT 5,
            supplier VARCHAR(100),
            active BOOLEAN DEFAULT TRUE
        )",
        
        "CREATE TABLE IF NOT EXISTS service_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            vehicle_id INT NOT NULL,
            service_date DATE NOT NULL,
            service_type VARCHAR(100) NOT NULL,
            description TEXT,
            mileage INT,
            next_service_mileage INT,
            next_service_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
        )",
        
        "CREATE TABLE IF NOT EXISTS callbacks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            message TEXT,
            best_time ENUM('morning', 'afternoon', 'evening') NULL,
            status ENUM('new', 'contacted', 'completed', 'cancelled') DEFAULT 'new',
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            contacted_at TIMESTAMP NULL,
            notes TEXT
        )",
        
        "CREATE TABLE IF NOT EXISTS chat_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(128) NOT NULL,
            message TEXT NOT NULL,
            sender_type ENUM('client', 'operator') NOT NULL,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            read_at TIMESTAMP NULL,
            INDEX idx_session_id (session_id),
            INDEX idx_created_at (created_at)
        )"
    ];
    
    foreach ($tables as $sql) {
        try {
            $connection->exec($sql);
        } catch (PDOException $e) {
            echo "Ошибка создания таблицы: " . $e->getMessage() . "<br>";
        }
    }
    
    // Создание администратора по умолчанию
    $adminExists = $db->fetch("SELECT id FROM users WHERE username = ?", ['admin']);
    if (!$adminExists) {
        $db->insert('users', [
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_BCRYPT, ['cost' => HASH_COST]),
            'full_name' => 'Администратор',
            'role' => 'admin'
        ]);
    }
    
    // Добавление базовых услуг
    $servicesExist = $db->fetch("SELECT id FROM services LIMIT 1");
    if (!$servicesExist) {
        $basicServices = [
            ['name' => 'Замена масла', 'description' => 'Замена моторного масла и фильтра', 'price' => 1500, 'duration' => 60, 'category' => 'Техобслуживание'],
            ['name' => 'Замена тормозных колодок', 'description' => 'Замена передних тормозных колодок', 'price' => 3000, 'duration' => 120, 'category' => 'Тормозная система'],
            ['name' => 'Диагностика двигателя', 'description' => 'Компьютерная диагностика двигателя', 'price' => 800, 'duration' => 30, 'category' => 'Диагностика'],
            ['name' => 'Замена аккумулятора', 'description' => 'Замена автомобильного аккумулятора', 'price' => 2000, 'duration' => 45, 'category' => 'Электрооборудование']
        ];
        
        foreach ($basicServices as $service) {
            $db->insert('services', $service);
        }
    }
}

// Создание таблиц при подключении можно отключать флагом в config.php
if (!defined('RUN_INSTALL') || RUN_INSTALL === true) {
	createTables();
}
?>
