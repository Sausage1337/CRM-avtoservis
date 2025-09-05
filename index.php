<?php
session_start();
require_once 'config/config.php';
require_once 'config/database.php';

// Простой роутер
$page = $_GET['page'] ?? 'login';

// Таймаут сессии и авто-логаут
$now = time();
if (isset($_SESSION['LAST_ACTIVITY']) && ($now - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {
	// Сессия протухла — разлогиниваем и перенаправляем в соответствующую форму
	$redirect = (isset($_SESSION['client_id'])) ? 'client_login' : 'login';
	session_unset();
	session_destroy();
	header('Location: index.php?page=' . $redirect . '&timeout=1');
	exit();
}
$_SESSION['LAST_ACTIVITY'] = $now;

// Передаем переменную $page в глобальную область видимости для использования в layout.php
global $page;

// Проверка авторизации
// Разрешаем публичные страницы админки и кабинета клиента на верхнем уровне роутера,
// чтобы клиентский портал сам выполнил свою проверку сессии и редирект
$publicPages = ['login', 'register', 'client_login', 'client_register', 'client_forgot_password', 'client_portal'];
if (!isset($_SESSION['user_id']) && !in_array($page, $publicPages, true)) {
	header('Location: index.php?page=login');
	exit();
}

// Подключение контроллеров
switch ($page) {
    case 'login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
        
    case 'register':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;
        
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'vehicles':
        require_once 'controllers/VehicleController.php';
        $controller = new VehicleController();
        $action = $_GET['action'] ?? 'index';
        
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'delete':
                $controller->delete();
                break;
            case 'getVehicleInfo':
                $controller->getVehicleInfo();
                break;
            default:
                $controller->index();
                break;
        }
        break;
        
    case 'clients':
        require_once 'controllers/ClientController.php';
        $controller = new ClientController();
        $action = $_GET['action'] ?? 'index';
        
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'delete':
                $controller->delete();
                break;
            case 'view':
                $controller->view();
                break;
            default:
                $controller->index();
                break;
        }
        break;
        
    case 'orders':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $action = $_GET['action'] ?? 'index';
        
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'addPart':
                $controller->addPart();
                break;
            case 'removePart':
                $controller->removePart();
                break;
            case 'delete':
                $controller->delete();
                break;
            case 'view':
                $controller->view();
                break;
            case 'listParts':
                $controller->listParts();
                break;
            case 'inventoryOptions':
                $controller->inventoryOptions();
                break;
            case 'validatePromo':
                $controller->validatePromo();
                break;
            case 'updateStatus':
                $controller->updateStatus();
                break;
            default:
                $controller->index();
                break;
        }
        break;
        
    case 'calculator':
        require_once 'controllers/CalculatorController.php';
        $controller = new CalculatorController();
        $controller->index();
        break;
        
    case 'inventory':
        require_once 'controllers/InventoryController.php';
        $controller = new InventoryController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'scan':
                $controller->scan();
                break;
            case 'audits':
                $controller->audits();
                break;
            case 'start_audit':
                $controller->startAudit();
                break;
            case 'complete_audit':
                $controller->completeAudit();
                break;
            case 'activity_log':
                $controller->activityLog();
                break;
            case 'movements':
                $controller->movements();
                break;
            case 'suppliers':
                $controller->suppliers();
                break;
            case 'supplier_orders':
                $controller->supplierOrders();
                break;
            case 'supplier_quick_draft':
                $controller->supplierQuickDraft();
                break;
            case 'supplier_confirm':
                $controller->supplierConfirm();
                break;
            case 'supplier_receive':
                $controller->supplierReceive();
                break;
            case 'purchase':
                $controller->purchase();
                break;
            default:
                $controller->index();
                break;
        }
        break;
        
                case 'reports':
        require_once 'controllers/ReportController.php';
        $controller = new ReportController();
        $controller->index();
        break;
        
    case 'process':
        require_once 'controllers/ProcessController.php';
        $controller = new ProcessController();
        $action = $_GET['action'] ?? 'index';
        
        switch ($action) {
            case 'calculation':
                $controller->calculation();
                break;
            case 'client_booking':
                $controller->clientBooking();
                break;
            case 'work_order':
                $controller->workOrder();
                break;
            case 'view_process':
                $controller->viewProcess();
                break;
            case 'link_process':
                $controller->linkProcess();
                break;
            case 'get_client_info':
                $controller->getClientInfo();
                break;
            case 'get_vehicle_info':
                $controller->getVehicleInfo();
                break;
            case 'get_client_vehicles':
                $controller->getClientVehicles();
                break;
            default:
                $controller->index();
                break;
        }
        break;
        
        // Личный кабинет клиентов
        case 'client_login':
            require_once 'controllers/ClientAuthController.php';
            $controller = new ClientAuthController();
            $controller->login();
            break;
        
        case 'client_register':
            require_once 'controllers/ClientAuthController.php';
            $controller = new ClientAuthController();
            $controller->register();
            break;
        
        case 'client_logout':
            require_once 'controllers/ClientAuthController.php';
            $controller = new ClientAuthController();
            $controller->logout();
            break;
        
        case 'client_forgot_password':
            require_once 'controllers/ClientAuthController.php';
            $controller = new ClientAuthController();
            $controller->forgotPassword();
            break;
        
        case 'client_portal':
            require_once 'controllers/ClientPortalController.php';
            $controller = new ClientPortalController();
            $action = $_GET['action'] ?? 'index';
            
            switch ($action) {
                case 'orders':
                    $controller->orders();
                    break;
                case 'order_details':
                    $controller->orderDetails();
                    break;
                case 'vehicles':
                    $controller->vehicles();
                    break;
                case 'service_book':
                    $controller->serviceBook();
                    break;
                case 'chat':
                    $controller->chat();
                    break;
                case 'loyalty':
                    $controller->loyalty();
                    break;
                case 'payment':
                    $controller->payment();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;
        
        case 'logout':
            session_destroy();
            header('Location: index.php?page=login');
            exit();
            break;

        case 'users':
            require_once 'controllers/UserController.php';
            $controller = new UserController();
            $action = $_GET['action'] ?? 'roles';
            switch ($action) {
                case 'updateRole':
                    $controller->updateRole();
                    break;
                default:
                    $controller->roles();
                    break;
            }
            break;
            
            case 'services':
        require_once 'controllers/ServiceController.php';
        $controller = new ServiceController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'delete':
                $controller->delete();
                break;
            case 'toggle':
                $controller->toggle();
                break;
            default:
                $controller->index();
                break;
        }
        break;
        
    case 'contact':
        require_once 'controllers/ContactController.php';
        $controller = new ContactController();
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'callback':
                $controller->callback();
                break;
            case 'chat':
                $controller->chat();
                break;
            case 'getChatMessages':
                $controller->getChatMessages();
                break;
            default:
                $controller->index();
                break;
        }
        break;
        
        default:
            // Если пользователь авторизован - на дашборд, иначе - на логин
            if (isset($_SESSION['user_id'])) {
                header('Location: index.php?page=dashboard');
            } else {
                header('Location: index.php?page=login');
            }
            exit();
}
?>
