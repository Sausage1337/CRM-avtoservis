-- Обновление базы данных для добавления функционала контактов
-- Выполните этот файл в вашей MySQL базе данных

-- Таблица для заявок на обратный звонок
CREATE TABLE IF NOT EXISTS callbacks (
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
);

-- Таблица для сообщений чата
CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(128) NOT NULL,
    message TEXT NOT NULL,
    sender_type ENUM('client', 'operator') NOT NULL,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    INDEX idx_session_id (session_id),
    INDEX idx_created_at (created_at)
);

-- Проверим, что таблицы созданы
SELECT 'Таблицы успешно созданы' as status;
SHOW TABLES LIKE 'callbacks';
SHOW TABLES LIKE 'chat_messages';
