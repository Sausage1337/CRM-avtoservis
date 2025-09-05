<?php
class OrderController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
        
        $orders = $this->getOrders($search, $status, $date_from, $date_to);
        $statuses = $this->getOrderStatuses();
        $clients = $this->getClients();
        $vehicles = $this->getVehicles();
        
        $this->renderView('orders/index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'statuses' => $statuses,
            'clients' => $clients,
            'vehicles' => $vehicles
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createOrder();
        } else {
            $clients = $this->getClients();
            $vehicles = $this->getVehicles();
            $services = $this->getServices();
            
            $this->renderView('orders/create', [
                'clients' => $clients,
                'vehicles' => $vehicles,
                'services' => $services
            ]);
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=orders');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateOrder($id);
        } else {
            $order = $this->getOrder($id);
            $clients = $this->getClients();
            $vehicles = $this->getVehicles();
            $services = $this->getServices();
            $orderItems = $this->getOrderItems($id);
            
            if (!$order) {
                header('Location: index.php?page=orders');
                exit;
            }
            
            $this->renderView('orders/edit', [
                'order' => $order,
                'clients' => $clients,
                'vehicles' => $vehicles,
                'services' => $services,
                'orderItems' => $orderItems
            ]);
        }
    }
    
    public function view() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=orders');
            exit;
        }
        
        $order = $this->getOrder($id);
        $orderItems = $this->getOrderItems($id);
        $orderHistory = $this->getOrderHistory($id);
        
        if (!$order) {
            header('Location: index.php?page=orders');
            exit;
        }
        
        $this->renderView('orders/view', [
            'order' => $order,
            'orderItems' => $orderItems,
            'orderHistory' => $orderHistory
        ]);
    }
    
    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=orders');
            exit;
        }
        
        try {
            $this->db->beginTransaction();
            
            // Удаляем элементы заказа
            try {
                $orderItemsExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_items'");
                if ($orderItemsExists) {
                    $this->db->execute("DELETE FROM order_items WHERE order_id = ?", [$id]);
                }
            } catch (Exception $e) {
                // Пропускаем если таблица не существует
            }
            
            // Удаляем историю заказа
            try {
                $historyExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_status_history'");
                if ($historyExists) {
                    $this->db->execute("DELETE FROM order_status_history WHERE order_id = ?", [$id]);
                }
            } catch (Exception $e) {
                // Пропускаем если таблица не существует
            }
            
            // Удаляем сам заказ
            $this->db->execute("DELETE FROM orders WHERE id = ?", [$id]);
            
            $this->db->commit();
            $_SESSION['success'] = 'Заказ успешно удален';
        } catch (Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Ошибка при удалении заказа: ' . $e->getMessage();
        }
        
        header('Location: index.php?page=orders');
        exit;
    }
    
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
            return;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
        $comment = $_POST['comment'] ?? '';
        
        if (!$orderId || !$newStatus) {
            echo json_encode(['success' => false, 'message' => 'Не указаны обязательные параметры']);
            return;
        }
        
        try {
            $this->db->beginTransaction();
            
            // Обновляем статус заказа
            $this->db->execute("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $orderId]);

            // Авто-списание зарезервированных запчастей при завершении
            if ($newStatus === 'completed') {
                $parts = $this->db->fetchAll("SELECT op.id, op.item_id, op.quantity FROM order_parts op WHERE op.order_id = ? AND op.consumed = 0", [$orderId]);
                foreach ($parts as $p) {
                    // Списание
                    $this->db->execute(
                        "INSERT INTO inventory_movements (item_id, type, quantity, unit_price, related_order_id, note, created_at, created_by) VALUES (?,?,?,?,?, ?, NOW(), ?)",
                        [$p['item_id'], 'out', (int)$p['quantity'], 0, $orderId, 'Авто-списание при завершении заказа', $_SESSION['user_id'] ?? null]
                    );
                    // Снятие резерва
                    $this->db->execute("UPDATE inventory_items SET reserved = GREATEST(reserved - ?, 0) WHERE id = ?", [(int)$p['quantity'], (int)$p['item_id']]);
                    $this->db->execute("UPDATE order_parts SET consumed = 1 WHERE id = ?", [(int)$p['id']]);
                }
            }
            
            // Добавляем запись в историю
            try {
                $historyExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_status_history'");
                if ($historyExists) {
                    $this->db->execute("
                        INSERT INTO order_status_history (order_id, status, comment, created_by, created_at) 
                        VALUES (?, ?, ?, ?, NOW())
                    ", [$orderId, $newStatus, $comment, $_SESSION['user_id'] ?? 1]);
                }
            } catch (Exception $e) {
                // Пропускаем если таблица не существует
            }
            
            $this->db->commit();
            echo json_encode(['success' => true, 'message' => 'Статус заказа обновлен']);
        } catch (Exception $e) {
            $this->db->rollback();
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    // Добавление запчасти к заказу с резервированием
    public function addPart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; return; }
        $orderId = (int)($_POST['order_id'] ?? 0);
        $itemId = (int)($_POST['item_id'] ?? 0);
        $qty = (int)($_POST['quantity'] ?? 0);
        if (!$orderId || !$itemId || $qty <= 0) { http_response_code(400); echo 'Bad Request'; return; }
        try {
            $this->db->beginTransaction();
            $price = (float)($this->db->fetchOne("SELECT price FROM inventory_items WHERE id = ?", [$itemId]) ?? 0);
            $this->db->execute("INSERT INTO order_parts (order_id, item_id, quantity, unit_price, total_price, reserved, consumed) VALUES (?,?,?,?,?,1,0)", [$orderId, $itemId, $qty, $price, $price * $qty]);
            // Резервируем на складе
            $this->db->execute("UPDATE inventory_items SET reserved = reserved + ? WHERE id = ?", [$qty, $itemId]);
            $this->db->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $this->db->rollback();
            http_response_code(500); echo 'Server Error';
        }
    }

    // Удаление запчасти из заказа с снятием резерва
    public function removePart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; return; }
        $orderPartId = (int)($_POST['order_part_id'] ?? 0);
        if (!$orderPartId) { http_response_code(400); echo 'Bad Request'; return; }
        try {
            $this->db->beginTransaction();
            $row = $this->db->fetch("SELECT item_id, quantity, reserved, consumed FROM order_parts WHERE id = ?", [$orderPartId]);
            if ($row && (int)$row['reserved'] === 1 && (int)$row['consumed'] === 0) {
                $this->db->execute("UPDATE inventory_items SET reserved = reserved - ? WHERE id = ?", [(int)$row['quantity'], (int)$row['item_id']]);
            }
            $this->db->execute("DELETE FROM order_parts WHERE id = ?", [$orderPartId]);
            $this->db->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $this->db->rollback();
            http_response_code(500); echo 'Server Error';
        }
    }

    public function listParts() {
        header('Content-Type: text/html; charset=utf-8');
        $orderId = (int)($_GET['order_id'] ?? 0);
        if (!$orderId) { echo '<div class="text-muted">Нет данных</div>'; return; }
        $rows = $this->db->fetchAll("SELECT op.id, op.quantity, op.unit_price, op.total_price, op.reserved, op.consumed, ii.part_name FROM order_parts op JOIN inventory_items ii ON ii.id = op.item_id WHERE op.order_id = ? ORDER BY op.id DESC", [$orderId]);
        if (empty($rows)) { echo '<div class="text-muted">Запчасти не добавлены</div>'; return; }
        echo '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Наименование</th><th>Кол-во</th><th>Цена</th><th>Сумма</th><th></th></tr></thead><tbody>';
        foreach ($rows as $r) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($r['part_name']) . '</td>';
            echo '<td><span class="badge bg-secondary">' . (int)$r['quantity'] . '</span></td>';
            echo '<td>' . number_format($r['unit_price'], 0, ',', ' ') . ' ₽</td>';
            echo '<td><strong>' . number_format($r['total_price'], 0, ',', ' ') . ' ₽</strong></td>';
            echo '<td>';
            if ((int)$r['consumed'] === 0) {
                echo '<button class="btn btn-sm btn-outline-danger" onclick="removePart(' . (int)$r['id'] . ')"><i class="fas fa-trash"></i></button>';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }

    public function inventoryOptions() {
        header('Content-Type: application/json');
        $items = $this->db->fetchAll("SELECT id, part_name FROM inventory_items WHERE active = 1 ORDER BY part_name");
        echo json_encode(['items' => $items]);
    }
    
    private function createOrder() {
        $clientId = $_POST['client_id'] ?? null;
        $vehicleId = $_POST['vehicle_id'] ?? null;
        $notes = $_POST['description'] ?? '';
        $priority = $_POST['priority'] ?? 'normal';
        $estimatedCost = $_POST['estimated_cost'] ?? 0;
        $services = $_POST['services'] ?? [];
        
        if (!$clientId || !$vehicleId) {
            $_SESSION['error'] = 'Не указаны обязательные поля';
            header('Location: index.php?page=orders&action=create');
            exit;
        }
        
        try {
            $this->db->beginTransaction();
            
            // Создаем заказ
            $orderNumber = 'ORD-' . date('Ymd') . '-' . substr((string)time(), -5) . '-' . random_int(100, 999);
            $orderId = $this->db->execute("
                INSERT INTO orders (order_number, client_id, vehicle_id, notes, total_amount, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'new', NOW())
            ", [$orderNumber, $clientId, $vehicleId, $notes, $estimatedCost]);
            
            // Добавляем услуги
            if (!empty($services)) {
                try {
                    $orderItemsExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_items'");
                    $servicesExists = $this->db->fetchOne("SHOW TABLES LIKE 'services'");
                    
                    if ($orderItemsExists && $servicesExists) {
                        foreach ($services as $serviceId) {
                            $service = $this->db->fetch("SELECT id, name, price FROM services WHERE id = ?", [$serviceId]);
                            if ($service) {
                                $price = (float)($service['price'] ?? 0);
                                $this->db->execute("
                                    INSERT INTO order_items (order_id, service_id, quantity, unit_price, total_price) 
                                    VALUES (?, ?, 1, ?, ?)
                                ", [$orderId, $serviceId, $price, $price]);
                            }
                        }
                    }
                } catch (Exception $e) {
                    // Пропускаем добавление услуг если таблицы не существуют
                }
            }

            // Добавляем свои услуги (без service_id)
            $customServices = $_POST['custom_services'] ?? [];
            if (!empty($customServices)) {
                foreach ($customServices as $cs) {
                    $name = trim($cs['name'] ?? '');
                    $price = (float)($cs['price'] ?? 0);
                    if ($name !== '' && $price > 0) {
                        $this->db->execute("INSERT INTO order_items (order_id, service_id, quantity, unit_price, total_price, notes) VALUES (?, NULL, 1, ?, ?, ?)", [$orderId, $price, $price, $name]);
                    }
                }
            }
            
            // Добавляем запись в историю
            try {
                $historyExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_status_history'");
                if ($historyExists) {
                    $this->db->execute("
                        INSERT INTO order_status_history (order_id, status, comment, created_by, created_at) 
                        VALUES (?, 'new', 'Заказ создан', ?, NOW())
                    ", [$orderId, $_SESSION['user_id'] ?? 1]);
                }
            } catch (Exception $e) {
                // Пропускаем добавление истории если таблица не существует
            }
            
            $this->db->commit();
            $_SESSION['success'] = 'Заказ успешно создан';
            header('Location: index.php?page=orders');
        } catch (Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Ошибка при создании заказа: ' . $e->getMessage();
            header('Location: index.php?page=orders&action=create');
        }
        exit;
    }
    
    private function updateOrder($id) {
        $clientId = $_POST['client_id'] ?? null;
        $vehicleId = $_POST['vehicle_id'] ?? null;
        $notes = $_POST['description'] ?? '';
        $priority = $_POST['priority'] ?? 'normal';
        $estimatedCost = $_POST['estimated_cost'] ?? 0;
        $status = $_POST['status'] ?? 'new';
        $services = $_POST['services'] ?? [];
        
        if (!$clientId || !$vehicleId) {
            $_SESSION['error'] = 'Не указаны обязательные поля';
            header('Location: index.php?page=orders&action=edit&id=' . $id);
            exit;
        }
        
        try {
            $this->db->beginTransaction();
            
            // Обновляем заказ
            $this->db->execute("
                UPDATE orders SET 
                    client_id = ?, vehicle_id = ?, notes = ?, total_amount = ?, status = ?
                WHERE id = ?
            ", [$clientId, $vehicleId, $notes, $estimatedCost, $status, $id]);
            
            // Удаляем старые элементы заказа
            try {
                $orderItemsExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_items'");
                if ($orderItemsExists) {
                    $this->db->execute("DELETE FROM order_items WHERE order_id = ?", [$id]);
                }
            } catch (Exception $e) {
                // Пропускаем если таблица не существует
            }
            
            // Добавляем новые услуги
            if (!empty($services)) {
                try {
                    $orderItemsExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_items'");
                    $servicesExists = $this->db->fetchOne("SHOW TABLES LIKE 'services'");
                    
                    if ($orderItemsExists && $servicesExists) {
                        foreach ($services as $serviceId) {
                            $price = (float)($this->db->fetchOne("SELECT price FROM services WHERE id = ?", [$serviceId]) ?? 0);
                            $this->db->execute("\n                                INSERT INTO order_items (order_id, service_id, quantity, unit_price, total_price) \n                                VALUES (?, ?, 1, ?, ?)\n                            ", [$id, $serviceId, $price, $price]);
                        }
                    }
                } catch (Exception $e) {
                    // Пропускаем если таблицы не существуют
                }
            }
            
            // Добавляем запись в историю если статус изменился
            try {
                $historyExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_status_history'");
                if ($historyExists) {
                    $oldStatus = $this->db->fetchOne("SELECT status FROM orders WHERE id = ?", [$id]);
                    if ($oldStatus !== $status) {
                        $this->db->execute("
                            INSERT INTO order_status_history (order_id, status, comment, created_by, created_at) 
                            VALUES (?, ?, 'Статус изменен', ?, NOW())
                        ", [$id, $status, $_SESSION['user_id'] ?? 1]);
                    }
                }
            } catch (Exception $e) {
                // Пропускаем если таблица не существует
            }
            
            $this->db->commit();
            $_SESSION['success'] = 'Заказ успешно обновлен';
            header('Location: index.php?page=orders');
        } catch (Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Ошибка при обновлении заказа: ' . $e->getMessage();
            header('Location: index.php?page=orders&action=edit&id=' . $id);
        }
        exit;
    }
    
    private function getOrders($search = '', $status = '', $dateFrom = '', $dateTo = '') {
        // Сначала проверяем, существует ли таблица order_items
        try {
            $tableExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_items'");
            if (!$tableExists) {
                // Если таблица не существует, возвращаем заказы без информации об элементах
                $sql = "SELECT o.*, c.full_name as client_name, c.phone as client_phone, 
                               v.license_plate, v.brand, v.model, v.year,
                               0 as items_count,
                               0 as total_amount
                        FROM orders o 
                        JOIN clients c ON o.client_id = c.id 
                        JOIN vehicles v ON o.vehicle_id = v.id";
            } else {
                // Если таблица существует, используем полный запрос
                $sql = "SELECT o.*, c.full_name as client_name, c.phone as client_phone, 
                               v.license_plate, v.brand, v.model, v.year,
                               COALESCE(COUNT(oi.id), 0) as items_count,
                               COALESCE(SUM(COALESCE(oi.total_price, oi.unit_price * oi.quantity)), 0) as total_amount
                        FROM orders o 
                        JOIN clients c ON o.client_id = c.id 
                        JOIN vehicles v ON o.vehicle_id = v.id
                        LEFT JOIN order_items oi ON o.id = oi.order_id";
            }
        } catch (Exception $e) {
            // В случае ошибки используем простой запрос
            $sql = "SELECT o.*, c.full_name as client_name, c.phone as client_phone, 
                           v.license_plate, v.brand, v.model, v.year,
                           0 as items_count,
                           0 as total_amount
                    FROM orders o 
                    JOIN clients c ON o.client_id = c.id 
                    JOIN vehicles v ON o.vehicle_id = v.id";
        }
        
        $params = [];
        $conditions = [];
        
        if (!empty($search)) {
            $conditions[] = "(o.order_number LIKE ? OR c.full_name LIKE ? OR v.license_plate LIKE ? OR o.notes LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($status)) {
            $conditions[] = "o.status = ?";
            $params[] = $status;
        }
        
        if (!empty($dateFrom)) {
            $conditions[] = "DATE(o.created_at) >= ?";
            $params[] = $dateFrom;
        }
        
        if (!empty($dateTo)) {
            $conditions[] = "DATE(o.created_at) <= ?";
            $params[] = $dateTo;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        // Добавляем GROUP BY только если используется таблица order_items
        if (strpos($sql, 'order_items') !== false) {
            $sql .= " GROUP BY o.id";
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    private function getOrder($id) {
        return $this->db->fetchOne("
            SELECT o.*, c.full_name as client_name, c.phone as client_phone, c.email as client_email,
                   v.license_plate, v.brand, v.model, v.year, v.vin, v.color
            FROM orders o 
            JOIN clients c ON o.client_id = c.id 
            JOIN vehicles v ON o.vehicle_id = v.id
            WHERE o.id = ?
        ", [$id]);
    }
    
    private function getOrderItems($orderId) {
        try {
            // Проверяем существование таблиц
            $orderItemsExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_items'");
            $servicesExists = $this->db->fetchOne("SHOW TABLES LIKE 'services'");
            
            if (!$orderItemsExists || !$servicesExists) {
                return [];
            }
            
            // Проверяем какие колонки существуют в таблице services
            $columns = $this->db->fetchAll("DESCRIBE services");
            $columnNames = array_column($columns, 'Field');
            
            $selectFields = "oi.id, oi.order_id, oi.service_id, oi.quantity, oi.unit_price as price, oi.total_price, s.name as service_name";
            
            if (in_array('description', $columnNames)) {
                $selectFields .= ", s.description as service_description";
            } else {
                $selectFields .= ", '' as service_description";
            }
            
            return $this->db->fetchAll("
                SELECT {$selectFields}
                FROM order_items oi
                JOIN services s ON oi.service_id = s.id
                WHERE oi.order_id = ?
                ORDER BY oi.id
            ", [$orderId]);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getOrderHistory($orderId) {
        try {
            // Проверяем существование таблицы
            $tableExists = $this->db->fetchOne("SHOW TABLES LIKE 'order_status_history'");
            
            if (!$tableExists) {
                return [];
            }
            
            return $this->db->fetchAll("
                SELECT osh.*, u.username as updated_by_name
                FROM order_status_history osh
                LEFT JOIN users u ON osh.created_by = u.id
                WHERE osh.order_id = ?
                ORDER BY osh.created_at DESC
            ", [$orderId]);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getOrderStatuses() {
        return [
            'new' => 'Новый',
            'confirmed' => 'Подтвержден',
            'in_progress' => 'В работе',
            'waiting_parts' => 'Ожидает запчасти',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен'
        ];
    }
    
    private function getClients() {
        return $this->db->fetchAll("SELECT id, full_name, phone FROM clients ORDER BY full_name");
    }
    
    private function getVehicles() {
        return $this->db->fetchAll("SELECT id, license_plate, brand, model, year FROM vehicles ORDER BY license_plate");
    }
    
    private function getServices() {
        try {
            $tableExists = $this->db->fetchOne("SHOW TABLES LIKE 'services'");
            if (!$tableExists) {
                return [];
            }
            
            // Сначала проверим, какие колонки реально существуют
            $columns = $this->db->fetchAll("DESCRIBE services");
            $columnNames = array_column($columns, 'Field');
            
            // Формируем запрос только с существующими колонками
            $selectColumns = ['id', 'name', 'price'];
            
            if (in_array('description', $columnNames)) {
                $selectColumns[] = 'description';
            }
            if (in_array('duration', $columnNames)) {
                $selectColumns[] = 'duration';
            }
            if (in_array('category', $columnNames)) {
                $selectColumns[] = 'category';
            }
            if (in_array('active', $columnNames)) {
                $selectColumns[] = 'active';
            }
            
            $selectClause = implode(', ', $selectColumns);
            $whereClause = in_array('active', $columnNames) ? 'WHERE active = 1' : '';
            $orderClause = in_array('category', $columnNames) ? 'ORDER BY category, name' : 'ORDER BY name';
            
            return $this->db->fetchAll("
                SELECT {$selectClause}
                FROM services 
                {$whereClause}
                {$orderClause}
            ");
        } catch (Exception $e) {
            // Логируем ошибку для отладки
            error_log("Error in getServices(): " . $e->getMessage());
            return [];
        }
    }
    
    private function renderView($view, $data = []) {
        // Убеждаемся, что переменная $page доступна
        global $page;
        if (!isset($page)) {
            $page = $_GET['page'] ?? 'orders';
        }
        
        extract($data);
        $content = VIEWS_PATH . "/{$view}.php";
        include VIEWS_PATH . "/layout.php";
    }
}
?>
