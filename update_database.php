<?php
require_once 'config/config.php';
require_once 'config/database.php';

// Проверяем, что это запрос от администратора (простая защита)
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    echo "<!DOCTYPE html>
<html>
<head>
    <title>Обновление базы данных</title>
    <meta charset='utf-8'>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .alert { padding: 15px; border-radius: 8px; margin: 20px 0; }
        .alert-warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .btn { padding: 10px 20px; background: #007aff; color: white; text-decoration: none; border-radius: 6px; margin: 10px; }
        .btn-secondary { background: #6c757d; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Обновление базы данных</h1>
        <div class='alert alert-warning'>
            <strong>Внимание!</strong> Это обновление добавит новые таблицы для функционала контактов:
            <ul>
                <li><code>callbacks</code> - заявки на обратный звонок</li>
                <li><code>chat_messages</code> - сообщения чата</li>
            </ul>
        </div>
        <p>Убедитесь, что у вас есть резервная копия базы данных перед выполнением обновления.</p>
        <a href='update_database.php?confirm=yes' class='btn'>Выполнить обновление</a>
        <a href='index.php' class='btn btn-secondary'>Отмена</a>
    </div>
</body>
</html>";
    exit();
}

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    echo "<!DOCTYPE html>
<html>
<head>
    <title>Обновление базы данных</title>
    <meta charset='utf-8'>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .success { color: #28a745; margin: 10px 0; }
        .error { color: #dc3545; margin: 10px 0; }
        .btn { padding: 10px 20px; background: #007aff; color: white; text-decoration: none; border-radius: 6px; margin: 10px; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 6px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Результат обновления базы данных</h1>";
    
    // Создание таблицы callbacks
    echo "<h3>Создание таблицы callbacks...</h3>";
    $callbacksSQL = "CREATE TABLE IF NOT EXISTS callbacks (
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
    )";
    
    try {
        $connection->exec($callbacksSQL);
        echo "<div class='success'>✅ Таблица callbacks успешно создана</div>";
    } catch (PDOException $e) {
        echo "<div class='error'>❌ Ошибка создания таблицы callbacks: " . $e->getMessage() . "</div>";
    }
    
    // Создание таблицы chat_messages
    echo "<h3>Создание таблицы chat_messages...</h3>";
    $chatSQL = "CREATE TABLE IF NOT EXISTS chat_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(128) NOT NULL,
        message TEXT NOT NULL,
        sender_type ENUM('client', 'operator') NOT NULL,
        ip_address VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        read_at TIMESTAMP NULL,
        INDEX idx_session_id (session_id),
        INDEX idx_created_at (created_at)
    )";
    
    try {
        $connection->exec($chatSQL);
        echo "<div class='success'>✅ Таблица chat_messages успешно создана</div>";
    } catch (PDOException $e) {
        echo "<div class='error'>❌ Ошибка создания таблицы chat_messages: " . $e->getMessage() . "</div>";
    }
    
    // Проверяем результат
    echo "<h3>Проверка созданных таблиц:</h3>";
    
    $tables = $connection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('callbacks', $tables)) {
        echo "<div class='success'>✅ Таблица callbacks найдена в базе данных</div>";
    } else {
        echo "<div class='error'>❌ Таблица callbacks не найдена</div>";
    }
    
    if (in_array('chat_messages', $tables)) {
        echo "<div class='success'>✅ Таблица chat_messages найдена в базе данных</div>";
    } else {
        echo "<div class='error'>❌ Таблица chat_messages не найдена</div>";
    }
    
    echo "<h3>Структура созданных таблиц:</h3>";
    
    // Показываем структуру таблицы callbacks
    if (in_array('callbacks', $tables)) {
        echo "<h4>Таблица callbacks:</h4>";
        $callbacksStructure = $connection->query("DESCRIBE callbacks")->fetchAll();
        echo "<pre>";
        foreach ($callbacksStructure as $column) {
            echo sprintf("%-20s %-20s %-10s %-10s %-10s %s\n", 
                $column['Field'], 
                $column['Type'], 
                $column['Null'], 
                $column['Key'], 
                $column['Default'], 
                $column['Extra']
            );
        }
        echo "</pre>";
    }
    
    // Показываем структуру таблицы chat_messages
    if (in_array('chat_messages', $tables)) {
        echo "<h4>Таблица chat_messages:</h4>";
        $chatStructure = $connection->query("DESCRIBE chat_messages")->fetchAll();
        echo "<pre>";
        foreach ($chatStructure as $column) {
            echo sprintf("%-20s %-20s %-10s %-10s %-10s %s\n", 
                $column['Field'], 
                $column['Type'], 
                $column['Null'], 
                $column['Key'], 
                $column['Default'], 
                $column['Extra']
            );
        }
        echo "</pre>";
    }
    
    echo "<div class='success'><strong>Обновление завершено!</strong> Теперь функционал контактов должен работать корректно.</div>";
    echo "<a href='index.php' class='btn'>Перейти к приложению</a>";
    echo "<a href='index.php?page=contact' class='btn'>Открыть страницу контактов</a>";
    
} catch (Exception $e) {
    echo "<div class='error'><strong>Критическая ошибка:</strong> " . $e->getMessage() . "</div>";
}

echo "
    </div>
</body>
</html>";
?>
