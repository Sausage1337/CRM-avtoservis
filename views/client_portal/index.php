<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-tachometer-alt me-2"></i>
        Добро пожаловать, <?= htmlspecialchars($client['full_name']) ?>!
    </h2>
    <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>
        <?= date('d.m.Y') ?>
    </div>
</div>

<!-- Статистика клиента -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number"><?= $stats['totalOrders'] ?></div>
            <div class="stats-label">Всего заказов</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number"><?= number_format($stats['totalSpent'], 0, ',', ' ') ?> ₽</div>
            <div class="stats-label">Общая сумма</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number"><?= $stats['totalVehicles'] ?></div>
            <div class="stats-label">Автомобилей</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number"><?= $stats['activeOrders'] ?></div>
            <div class="stats-label">Заказов в работе</div>
        </div>
    </div>
</div>

<!-- Активные заказы -->
<?php if (!empty($activeOrders)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>
                    Активные заказы
                </h5>
            </div>
            <div class="card-body">
                <?php foreach ($activeOrders as $order): ?>
                <div class="order-card">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <strong>Заказ #<?= htmlspecialchars($order['order_number']) ?></strong>
                            <br>
                            <small class="text-muted">
                                <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                            </small>
                        </div>
                        <div class="col-md-3">
                            <i class="fas fa-car me-2"></i>
                            <?= htmlspecialchars($order['brand']) ?> <?= htmlspecialchars($order['model']) ?>
                            <br>
                            <small class="text-muted">
                                <?= htmlspecialchars($order['license_plate']) ?>
                            </small>
                        </div>
                        <div class="col-md-3">
                            <span class="status-badge status-<?= $order['status'] ?>">
                                <?php
                                switch ($order['status']) {
                                    case 'new': echo 'Новый'; break;
                                    case 'in_progress': echo 'В работе'; break;
                                    default: echo ucfirst($order['status']);
                                }
                                ?>
                            </span>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="index.php?page=client_portal&action=order_details&id=<?= $order['id'] ?>" 
                               class="btn btn-client btn-sm">
                                <i class="fas fa-eye me-1"></i>
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Последние заказы -->
<?php if (!empty($recentOrders)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Последние заказы
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Номер заказа</th>
                                <th>Автомобиль</th>
                                <th>Статус</th>
                                <th>Сумма</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>
                                    <strong>#<?= htmlspecialchars($order['order_number']) ?></strong>
                                </td>
                                <td>
                                    <?= htmlspecialchars($order['brand']) ?> <?= htmlspecialchars($order['model']) ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($order['license_plate']) ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $order['status'] ?>">
                                        <?php
                                        switch ($order['status']) {
                                            case 'new': echo 'Новый'; break;
                                            case 'in_progress': echo 'В работе'; break;
                                            case 'completed': echo 'Завершен'; break;
                                            case 'cancelled': echo 'Отменен'; break;
                                            default: echo ucfirst($order['status']);
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($order['total_amount'] > 0): ?>
                                        <strong><?= number_format($order['total_amount'], 0, ',', ' ') ?> ₽</strong>
                                    <?php else: ?>
                                        <span class="text-muted">Не рассчитано</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= date('d.m.Y', strtotime($order['created_at'])) ?>
                                </td>
                                <td>
                                    <a href="index.php?page=client_portal&action=order_details&id=<?= $order['id'] ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Напоминания о ТО -->
<?php if (!empty($maintenanceReminders)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Напоминания о техническом обслуживании
                </h5>
            </div>
            <div class="card-body">
                <?php foreach ($maintenanceReminders as $reminder): ?>
                <div class="alert alert-warning mb-2">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <strong><?= htmlspecialchars($reminder['brand']) ?> <?= htmlspecialchars($reminder['model']) ?></strong>
                            <br>
                            <small class="text-muted">
                                <?= htmlspecialchars($reminder['license_plate']) ?>
                            </small>
                        </div>
                        <div class="col-md-3">
                            <i class="fas fa-calendar me-2"></i>
                            Следующее ТО: <?= date('d.m.Y', strtotime($reminder['next_service_date'])) ?>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="index.php?page=client_portal&action=service_book&vehicle_id=<?= $reminder['vehicle_id'] ?>" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-book me-1"></i>
                                Сервисная книга
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Быстрые действия -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Быстрые действия
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <a href="index.php?page=client_portal&action=chat" class="text-decoration-none">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-comments fa-2x text-primary mb-2"></i>
                                <div>Чат с сервисом</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <a href="index.php?page=client_portal&action=vehicles" class="text-decoration-none">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-car fa-2x text-success mb-2"></i>
                                <div>Мои автомобили</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <a href="index.php?page=client_portal&action=loyalty" class="text-decoration-none">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                <div>Программа лояльности</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <a href="index.php?page=client_portal&action=orders" class="text-decoration-none">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-clipboard-list fa-2x text-info mb-2"></i>
                                <div>Все заказы</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
