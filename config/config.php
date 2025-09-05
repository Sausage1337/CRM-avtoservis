<?php
// Конфигурация приложения
define('APP_NAME', 'CRM Автосервис');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost');
// Управление установкой БД (создание таблиц и демо-данных)
define('RUN_INSTALL', false);

// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'autoservice_crm');
define('DB_USER', 'root');
define('DB_PASS', '');

// Настройки безопасности
define('HASH_COST', 12);
define('SESSION_TIMEOUT', 3600); // 1 час

// API ключи для получения данных об автомобилях
define('VIN_API_KEY', ''); // Здесь нужно будет добавить API ключ
define('VIN_API_URL', 'https://api.example.com/vin/');

// Пути к файлам
define('ROOT_PATH', dirname(__DIR__));
define('VIEWS_PATH', ROOT_PATH . '/views');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Настройки загрузки файлов
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Настройки отчетов
define('REPORTS_PATH', ROOT_PATH . '/reports');
define('REPORTS_FORMAT', 'pdf'); // pdf, excel, csv

// Настройки уведомлений
define('EMAIL_ENABLED', false);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
?>
