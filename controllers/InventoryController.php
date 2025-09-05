<?php
class InventoryController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function index() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.view');
        $q = trim($_GET['q'] ?? '');
        $supplierId = $_GET['supplier_id'] ?? '';
        $lowOnly = isset($_GET['low_only']) && $_GET['low_only'] == '1';

        $sql = "SELECT i.*, b.current_stock, b.available_stock, s.name AS supplier_name
                FROM inventory_items i
                LEFT JOIN inventory_balances b ON b.item_id = i.id
                LEFT JOIN suppliers s ON s.id = i.supplier_id";
        $conds = [];
        $params = [];
        if ($q !== '') {
            $conds[] = "(i.part_name LIKE ? OR i.part_number LIKE ? OR i.barcode LIKE ?)";
            $like = "%{$q}%";
            $params = array_merge($params, [$like, $like, $like]);
        }
        if ($supplierId !== '') {
            $conds[] = "i.supplier_id = ?";
            $params[] = $supplierId;
        }
        if (!empty($conds)) {
            $sql .= " WHERE " . implode(' AND ', $conds);
        }
        $sql .= " ORDER BY i.part_name";
        $items = $this->db->fetchAll($sql, $params);

        // Низкий остаток список
        $lowStock = $this->db->fetchAll("SELECT * FROM inventory_balances WHERE current_stock < min_stock ORDER BY part_name");
        if ($lowOnly) {
            // фильтрация уже выбранных items по lowOnly
            $lowIds = array_column($lowStock, 'item_id');
            $items = array_values(array_filter($items, fn($x) => in_array($x['id'], $lowIds)));
        }
        $suppliers = $this->db->fetchAll("SELECT id, name FROM suppliers ORDER BY name");
        $this->renderView('inventory/index', [
            'items' => $items,
            'lowStock' => $lowStock,
            'q' => $q,
            'supplierId' => $supplierId,
            'lowOnly' => $lowOnly,
            'suppliers' => $suppliers
        ]);
    }

    public function movements() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.view');
        $itemId = $_GET['item_id'] ?? null;
        $params = [];
        $where = '';
        if ($itemId) { $where = 'WHERE im.item_id = ?'; $params[] = $itemId; }
        $movements = $this->db->fetchAll(
            "SELECT im.*, ii.part_name FROM inventory_movements im JOIN inventory_items ii ON ii.id = im.item_id $where ORDER BY im.created_at DESC",
            $params
        );
        $items = $this->db->fetchAll("SELECT id, part_name FROM inventory_items ORDER BY part_name");
        $this->renderView('inventory/movements', [
            'movements' => $movements,
            'items' => $items,
            'selectedItemId' => $itemId
        ]);
    }

    public function suppliers() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.supplier');
        $suppliers = $this->db->fetchAll("SELECT * FROM suppliers ORDER BY name");
        $this->renderView('inventory/suppliers', [ 'suppliers' => $suppliers ]);
    }

    public function purchase() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.purchase');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemId = $_POST['item_id'] ?? null;
            $quantity = (int)($_POST['quantity'] ?? 0);
            $unitPrice = (float)($_POST['unit_price'] ?? 0);
            if ($itemId && $quantity > 0) {
                $this->db->execute(
                    "INSERT INTO inventory_movements (item_id, type, quantity, unit_price, note, created_at, created_by) VALUES (?,?,?,?,?,NOW(),?)",
                    [$itemId, 'in', $quantity, $unitPrice, 'Приход по закупке', $_SESSION['user_id'] ?? null]
                );
                $this->logActivity('movement_in', 'inventory_item', (int)$itemId, ['qty' => (int)$quantity, 'price' => (float)$unitPrice, 'note' => 'Приход по закупке']);
            }
            header('Location: index.php?page=inventory&action=movements');
            exit();
        }
        $items = $this->db->fetchAll("SELECT id, part_name FROM inventory_items ORDER BY part_name");
        $this->renderView('inventory/purchase', [ 'items' => $items ]);
    }

    public function create() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.edit');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $partName = trim($_POST['part_name'] ?? '');
            $partNumber = trim($_POST['part_number'] ?? '');
            $unit = trim($_POST['unit'] ?? 'шт');
            $price = (float)($_POST['price'] ?? 0);
            $minStock = (int)($_POST['min_stock'] ?? 0);
            $initialStock = (int)($_POST['initial_stock'] ?? 0);
            if ($partName === '') {
                $_SESSION['error'] = 'Введите наименование позиции';
                header('Location: index.php?page=inventory&action=create');
                exit();
            }
            $itemId = $this->db->execute(
                "INSERT INTO inventory_items (part_name, part_number, unit, price, min_stock, stock, created_at) VALUES (?,?,?,?,?,?,NOW())",
                [$partName, $partNumber, $unit, $price, $minStock, 0]
            );
            if ($initialStock > 0) {
                $this->db->execute(
                    "INSERT INTO inventory_movements (item_id, type, quantity, unit_price, note, created_at, created_by) VALUES (?,?,?,?,?,NOW(),?)",
                    [$itemId, 'in', $initialStock, $price, 'Начальный остаток', $_SESSION['user_id'] ?? null]
                );
                $this->logActivity('movement_in', 'inventory_item', (int)$itemId, ['qty' => (int)$initialStock, 'price' => (float)$price, 'note' => 'Начальный остаток']);
            }
            $this->logActivity('create_item', 'inventory_item', (int)$itemId, ['name' => $partName, 'part_number' => $partNumber]);
            $_SESSION['success'] = 'Позиция создана';
            header('Location: index.php?page=inventory');
            exit();
        }
        $this->renderView('inventory/create', []);
    }

    private function ensureInventorySchema() {
        // Создаем необходимые таблицы/представления, если их нет
        try {
            $pdo = $this->db->getConnection();
            $pdo->exec("CREATE TABLE IF NOT EXISTS inventory_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                part_name VARCHAR(150) NOT NULL,
                part_number VARCHAR(100),
                unit VARCHAR(20) DEFAULT 'шт',
                unit_factor DECIMAL(10,4) DEFAULT 1,
                price DECIMAL(10,2) DEFAULT 0,
                min_stock INT DEFAULT 0,
                stock INT DEFAULT 0,
                reserved INT DEFAULT 0,
                barcode VARCHAR(64) NULL,
                supplier_id INT NULL,
                active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            // Попытка добавить недостающие колонки (если таблица уже была создана ранее)
            try { $pdo->exec("ALTER TABLE inventory_items ADD COLUMN reserved INT DEFAULT 0"); } catch (Exception $e) {}
            try { $pdo->exec("ALTER TABLE inventory_items ADD COLUMN barcode VARCHAR(64) NULL"); } catch (Exception $e) {}
            try { $pdo->exec("ALTER TABLE inventory_items ADD COLUMN unit_factor DECIMAL(10,4) DEFAULT 1"); } catch (Exception $e) {}
            $pdo->exec("CREATE TABLE IF NOT EXISTS suppliers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                phone VARCHAR(50),
                email VARCHAR(100),
                address TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            $pdo->exec("CREATE TABLE IF NOT EXISTS inventory_movements (
                id INT AUTO_INCREMENT PRIMARY KEY,
                item_id INT NOT NULL,
                type ENUM('in','out','adjust') NOT NULL,
                quantity INT NOT NULL,
                unit_price DECIMAL(10,2) DEFAULT 0,
                related_order_id INT NULL,
                note TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                created_by INT NULL,
                FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE CASCADE
            )");
            // Заказ-список запчастей в заказе клиента
            $pdo->exec("CREATE TABLE IF NOT EXISTS order_parts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                item_id INT NOT NULL,
                quantity INT NOT NULL,
                unit_price DECIMAL(10,2) DEFAULT 0,
                total_price DECIMAL(10,2) DEFAULT 0,
                reserved TINYINT(1) DEFAULT 1,
                consumed TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE RESTRICT
            )");
            // Заказы поставщикам
            $pdo->exec("CREATE TABLE IF NOT EXISTS supplier_orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                supplier_id INT NULL,
                status ENUM('draft','confirmed','received','cancelled') DEFAULT 'draft',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
            )");
            $pdo->exec("CREATE TABLE IF NOT EXISTS supplier_order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                supplier_order_id INT NOT NULL,
                item_id INT NOT NULL,
                quantity INT NOT NULL,
                unit_price DECIMAL(10,2) DEFAULT 0,
                total_price DECIMAL(10,2) DEFAULT 0,
                FOREIGN KEY (supplier_order_id) REFERENCES supplier_orders(id) ON DELETE CASCADE,
                FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE RESTRICT
            )");
            // Представление текущих остатков
            $pdo->exec("CREATE OR REPLACE VIEW inventory_balances AS
                SELECT 
                    i.id as item_id,
                    i.part_name,
                    i.part_number,
                    i.unit,
                    i.unit_factor,
                    i.min_stock,
                    i.price,
                    i.stock + IFNULL((SELECT SUM(CASE 
                        WHEN im.type='in' THEN im.quantity 
                        WHEN im.type='out' THEN -im.quantity 
                        WHEN im.type='adjust' THEN im.quantity 
                        ELSE 0 END) 
                        FROM inventory_movements im WHERE im.item_id=i.id),0) AS current_stock,
                    (i.stock + IFNULL((SELECT SUM(CASE 
                        WHEN im.type='in' THEN im.quantity 
                        WHEN im.type='out' THEN -im.quantity 
                        WHEN im.type='adjust' THEN im.quantity 
                        ELSE 0 END) 
                        FROM inventory_movements im WHERE im.item_id=i.id),0) - i.reserved) AS available_stock
                FROM inventory_items i");

            // Инвентаризация (аудит)
            $pdo->exec("CREATE TABLE IF NOT EXISTS inventory_audits (
                id INT AUTO_INCREMENT PRIMARY KEY,
                status ENUM('draft','completed','cancelled') DEFAULT 'draft',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                completed_at TIMESTAMP NULL,
                created_by INT NULL
            )");
            $pdo->exec("CREATE TABLE IF NOT EXISTS inventory_audit_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                audit_id INT NOT NULL,
                item_id INT NOT NULL,
                expected_qty INT DEFAULT 0,
                counted_qty INT DEFAULT 0,
                diff INT DEFAULT 0,
                FOREIGN KEY (audit_id) REFERENCES inventory_audits(id) ON DELETE CASCADE,
                FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE CASCADE
            )");
            $pdo->exec("CREATE TABLE IF NOT EXISTS inventory_activity_log (
                id INT AUTO_INCREMENT PRIMARY KEY,
                action VARCHAR(50) NOT NULL,
                entity VARCHAR(50) NOT NULL,
                entity_id INT NULL,
                details TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                user_id INT NULL
            )");
        } catch (Exception $e) {
            // Молча пропускаем, чтобы не ломать приложение — ошибка будет видна в логах БД
        }
    }

    // Форма сканирования: barcode + qty + type(in/out). Конверсия учитывает unit_factor.
    public function scan() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.scan', false);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; return; }
        $barcode = trim($_POST['barcode'] ?? '');
        $type = $_POST['type'] ?? 'in';
        $qty = (int)($_POST['quantity'] ?? 0);
        if ($barcode === '' || $qty <= 0) { http_response_code(400); echo 'Bad Request'; return; }
        $item = $this->db->fetch("SELECT id, unit_factor, price FROM inventory_items WHERE barcode = ?", [$barcode]);
        if (!$item) { http_response_code(404); echo 'Not Found'; return; }
        $applyQty = max(1, (int)round($qty * (float)($item['unit_factor'] ?? 1)));
        $this->db->execute(
            "INSERT INTO inventory_movements (item_id, type, quantity, unit_price, note, created_at, created_by) VALUES (?,?,?,?,?,NOW(),?)",
            [$item['id'], ($type === 'out' ? 'out' : 'in'), $applyQty, (float)($item['price'] ?? 0), 'Сканер', $_SESSION['user_id'] ?? null]
        );
        $this->logActivity(($type === 'out' ? 'movement_scan_out' : 'movement_scan_in'), 'inventory_item', (int)$item['id'], ['barcode' => $barcode, 'qty' => $applyQty]);
        echo json_encode(['success' => true]);
    }

    // Аудит: список и завершение пересортицы с созданием adjust движений по diff
    public function audits() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.audit');
        $audits = $this->db->fetchAll("SELECT * FROM inventory_audits ORDER BY id DESC");
        $this->renderView('inventory/audits', ['audits' => $audits]);
    }

    public function startAudit() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.audit');
        $auditId = $this->db->execute("INSERT INTO inventory_audits (status, created_at, created_by) VALUES ('draft', NOW(), ?)", [$_SESSION['user_id'] ?? null]);
        // Заполняем ожидаемое количество из balances
        $rows = $this->db->fetchAll("SELECT item_id, current_stock FROM inventory_balances");
        foreach ($rows as $r) {
            $this->db->execute("INSERT INTO inventory_audit_items (audit_id, item_id, expected_qty, counted_qty, diff) VALUES (?,?,?,?,0)", [$auditId, $r['item_id'], (int)$r['current_stock'], 0]);
        }
        $this->logActivity('audit_start', 'inventory_audit', (int)$auditId, []);
        header('Location: index.php?page=inventory&action=audits');
        exit();
    }

    public function completeAudit() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.audit');
        $auditId = (int)($_POST['audit_id'] ?? 0);
        if (!$auditId) { header('Location: index.php?page=inventory&action=audits'); exit(); }
        // Пересчитываем diff и создаем adjust для отличий
        $items = $this->db->fetchAll("SELECT item_id, expected_qty, counted_qty FROM inventory_audit_items WHERE audit_id = ?", [$auditId]);
        foreach ($items as $it) {
            $diff = (int)$it['counted_qty'] - (int)$it['expected_qty'];
            if ($diff !== 0) {
                $this->db->execute(
                    "INSERT INTO inventory_movements (item_id, type, quantity, unit_price, note, created_at, created_by) VALUES (?,?,?,?,?,NOW(),?)",
                    [$it['item_id'], 'adjust', $diff, 0, 'Инвентаризация #' . $auditId, $_SESSION['user_id'] ?? null]
                );
            }
        }
        $this->db->execute("UPDATE inventory_audits SET status='completed', completed_at=NOW() WHERE id = ?", [$auditId]);
        $this->logActivity('audit_complete', 'inventory_audit', (int)$auditId, []);
        header('Location: index.php?page=inventory&action=audits');
        exit();
    }

    public function activityLog() {
        $this->ensureInventorySchema();
        $this->ensurePermission('inventory.view');
        $log = $this->db->fetchAll("SELECT * FROM inventory_activity_log ORDER BY id DESC LIMIT 500");
        $this->renderView('inventory/activity_log', ['log' => $log]);
    }

    private function ensurePermission($permission, $isPage = true) {
        $role = $_SESSION['role'] ?? 'manager';
        $map = [
            'admin' => ['inventory.view','inventory.edit','inventory.purchase','inventory.scan','inventory.audit','inventory.supplier'],
            'manager' => ['inventory.view','inventory.edit','inventory.purchase','inventory.scan','inventory.supplier'],
            'mechanic' => ['inventory.view','inventory.scan']
        ];
        $allowed = in_array($permission, $map[$role] ?? []);
        if (!$allowed) {
            if ($isPage) {
                header('Location: index.php?page=dashboard&error=access');
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'forbidden']);
            }
            exit();
        }
    }

    private function logActivity($action, $entity, $entityId, $details = []) {
        $this->db->execute(
            "INSERT INTO inventory_activity_log (action, entity, entity_id, details, created_at, user_id) VALUES (?,?,?,?,NOW(),?)",
            [$action, $entity, $entityId, json_encode($details, JSON_UNESCAPED_UNICODE), $_SESSION['user_id'] ?? null]
        );
    }

    public function edit() {
        $this->ensureInventorySchema();
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: index.php?page=inventory'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $partName = trim($_POST['part_name'] ?? '');
            $partNumber = trim($_POST['part_number'] ?? '');
            $unit = trim($_POST['unit'] ?? 'шт');
            $price = (float)($_POST['price'] ?? 0);
            $minStock = (int)($_POST['min_stock'] ?? 0);
            if ($partName === '') { $_SESSION['error'] = 'Введите наименование'; header('Location: index.php?page=inventory&action=edit&id=' . $id); exit(); }
            $this->db->execute("UPDATE inventory_items SET part_name=?, part_number=?, unit=?, price=?, min_stock=? WHERE id=?",
                [$partName, $partNumber, $unit, $price, $minStock, $id]
            );
            // Корректировка остатка (опционально)
            if (isset($_POST['adjust_stock']) && $_POST['adjust_stock'] !== '') {
                $delta = (int)$_POST['adjust_stock'];
                if ($delta !== 0) {
                    $this->db->execute(
                        "INSERT INTO inventory_movements (item_id, type, quantity, unit_price, note, created_at, created_by) VALUES (?,?,?,?,?,NOW(),?)",
                        [$id, 'adjust', $delta, $price, 'Корректировка остатка', $_SESSION['user_id'] ?? null]
                    );
                }
            }
            $_SESSION['success'] = 'Позиция обновлена';
            header('Location: index.php?page=inventory');
            exit();
        }
        $item = $this->db->fetch("SELECT * FROM inventory_items WHERE id = ?", [$id]);
        $this->renderView('inventory/edit', ['item' => $item]);
    }

    private function renderView($view, $data = []) {
        global $page;
        if (!isset($page)) { $page = 'inventory'; }
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>


