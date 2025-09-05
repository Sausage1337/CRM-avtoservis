-- Дополнительные таблицы для личного кабинета клиентов

-- Таблица для истории статусов заказов
CREATE TABLE IF NOT EXISTS order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT, -- ID пользователя (сотрудника)
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Таблица для фото/видео отчетов по заказам
CREATE TABLE IF NOT EXISTS order_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    type ENUM('photo', 'video', 'document') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(500) NOT NULL,
    file_size INT,
    mime_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT, -- ID пользователя (сотрудника)
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Таблица для чата между клиентами и сервисом
CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    order_id INT, -- Может быть привязан к конкретному заказу
    user_id INT, -- ID сотрудника сервиса (NULL если сообщение от клиента)
    message TEXT NOT NULL,
    is_from_client BOOLEAN DEFAULT TRUE,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Таблица для уведомлений клиентов
CREATE TABLE IF NOT EXISTS client_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    type ENUM('order_status', 'maintenance', 'payment', 'general') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Таблица для программы лояльности
CREATE TABLE IF NOT EXISTS loyalty_program (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    level ENUM('Bronze', 'Silver', 'Gold', 'VIP') DEFAULT 'Bronze',
    points INT DEFAULT 0,
    total_spent DECIMAL(12,2) DEFAULT 0,
    discount_percent INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Таблица для истории лояльности
CREATE TABLE IF NOT EXISTS loyalty_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    order_id INT,
    action ENUM('earn', 'spend', 'level_up') NOT NULL,
    points_change INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
);

-- Таблица для платежей клиентов
CREATE TABLE IF NOT EXISTS client_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    client_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'online', 'transfer') NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255),
    payment_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Таблица для отзывов клиентов
CREATE TABLE IF NOT EXISTS client_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    client_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    is_public BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Индексы для оптимизации
CREATE INDEX idx_order_status_history_order_id ON order_status_history(order_id);
CREATE INDEX idx_order_reports_order_id ON order_reports(order_id);
CREATE INDEX idx_chat_messages_client_id ON chat_messages(client_id);
CREATE INDEX idx_chat_messages_order_id ON chat_messages(order_id);
CREATE INDEX idx_client_notifications_client_id ON client_notifications(client_id);
CREATE INDEX idx_loyalty_program_client_id ON loyalty_program(client_id);
CREATE INDEX idx_client_payments_order_id ON client_payments(order_id);
CREATE INDEX idx_client_reviews_order_id ON client_reviews(order_id);

-- Триггер для автоматического обновления истории статусов
DELIMITER //
CREATE TRIGGER after_order_status_update
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO order_status_history (order_id, status, description, created_by)
        VALUES (NEW.id, NEW.status, CONCAT('Статус изменен с "', OLD.status, '" на "', NEW.status, '"'), NULL);
    END IF;
END//
DELIMITER ;

-- Промо и скидки
CREATE TABLE IF NOT EXISTS promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    discount_percent INT DEFAULT 0,
    discount_fixed DECIMAL(10,2) DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    starts_at DATE NULL,
    ends_at DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS promo_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    discount_percent INT DEFAULT 0,
    discount_fixed DECIMAL(10,2) DEFAULT 0,
    max_uses INT DEFAULT 0,
    used_count INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    starts_at DATE NULL,
    ends_at DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_discounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    type ENUM('promotion','promo_code') NOT NULL,
    reference_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Склад и запчасти
CREATE TABLE IF NOT EXISTS inventory_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    part_name VARCHAR(150) NOT NULL,
    part_number VARCHAR(100),
    unit VARCHAR(20) DEFAULT 'шт',
    price DECIMAL(10,2) DEFAULT 0,
    min_stock INT DEFAULT 0,
    stock INT DEFAULT 0,
    supplier_id INT NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(50),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS inventory_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    type ENUM('in','out','adjust') NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) DEFAULT 0,
    related_order_id INT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT NULL,
    FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE CASCADE,
    FOREIGN KEY (related_order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE VIEW IF NOT EXISTS inventory_balances AS
SELECT 
    i.id as item_id,
    i.part_name,
    i.part_number,
    i.unit,
    i.min_stock,
    i.price,
    i.stock + IFNULL((SELECT SUM(CASE WHEN im.type='in' THEN im.quantity WHEN im.type='out' THEN -im.quantity ELSE 0 END) FROM inventory_movements im WHERE im.item_id=i.id),0) AS current_stock
FROM inventory_items i;

-- Триггер для автоматического обновления программы лояльности
DELIMITER //
CREATE TRIGGER after_order_completion
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.status != 'completed' AND NEW.status = 'completed' THEN
        -- Обновляем общую сумму потраченную клиентом
        UPDATE loyalty_program 
        SET total_spent = total_spent + NEW.total_amount,
            points = points + FLOOR(NEW.total_amount / 100), -- 1 балл за каждые 100 рублей
            updated_at = CURRENT_TIMESTAMP
        WHERE client_id = NEW.client_id;
        
        -- Проверяем повышение уровня
        UPDATE loyalty_program 
        SET level = CASE 
            WHEN total_spent >= 100000 THEN 'VIP'
            WHEN total_spent >= 50000 THEN 'Gold'
            WHEN total_spent >= 20000 THEN 'Silver'
            ELSE 'Bronze'
        END,
        discount_percent = CASE 
            WHEN total_spent >= 100000 THEN 15
            WHEN total_spent >= 50000 THEN 10
            WHEN total_spent >= 20000 THEN 5
            ELSE 0
        END
        WHERE client_id = NEW.client_id;
        
        -- Добавляем запись в историю лояльности
        INSERT INTO loyalty_history (client_id, order_id, action, points_change, description)
        VALUES (NEW.client_id, NEW.id, 'earn', FLOOR(NEW.total_amount / 100), 
                CONCAT('Заработано за заказ #', NEW.order_number));
    END IF;
END//
DELIMITER ;
