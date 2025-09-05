<!-- Apple-style Dashboard Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="apple-card p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2 fw-semibold">Автосервис</h1>
                    <p class="text-secondary mb-0">Добро пожаловать в панель управления</p>
                </div>
                <div class="text-end">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="text-center">
                            <div class="h5 mb-0 text-primary"><?= $stats['todayOrders'] ?></div>
                            <small class="text-secondary">Заказов</small>
                        </div>
                        <div class="text-center">
                            <div class="h5 mb-0 text-success"><?= number_format($stats['todayRevenue'], 0, ',', ' ') ?> ₽</div>
                            <small class="text-secondary">Выручка</small>
                        </div>
                    </div>
                    <a href="index.php?page=orders&action=create" class="btn btn-apple">
                        <i class="fas fa-plus me-2"></i>Новый заказ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apple-style Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="apple-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                    <i class="fas fa-users fa-lg"></i>
                </div>
                <div class="text-end">
                    <h3 class="mb-0 text-primary"><?= $stats['totalClients'] ?></h3>
                    <small class="text-secondary">Клиенты</small>
                </div>
            </div>
            <div class="progress bg-light" style="height: 4px;">
                <div class="progress-bar bg-primary" style="width: 75%"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="apple-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success rounded-3 p-3">
                    <i class="fas fa-car fa-lg"></i>
                </div>
                <div class="text-end">
                    <h3 class="mb-0 text-success"><?= $stats['totalVehicles'] ?></h3>
                    <small class="text-secondary">Автомобили</small>
                </div>
            </div>
            <div class="progress bg-light" style="height: 4px;">
                <div class="progress-bar bg-success" style="width: 85%"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="apple-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                    <i class="fas fa-clipboard-list fa-lg"></i>
                </div>
                <div class="text-end">
                    <h3 class="mb-0 text-warning"><?= $stats['todayOrders'] ?></h3>
                    <small class="text-secondary">Заказы сегодня</small>
                </div>
            </div>
            <div class="progress bg-light" style="height: 4px;">
                <div class="progress-bar bg-warning" style="width: 60%"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="apple-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info rounded-3 p-3">
                    <i class="fas fa-ruble-sign fa-lg"></i>
                </div>
                <div class="text-end">
                    <h3 class="mb-0 text-info"><?= number_format($stats['todayRevenue'], 0, ',', ' ') ?> ₽</h3>
                    <small class="text-secondary">Выручка</small>
                </div>
            </div>
            <div class="progress bg-light" style="height: 4px;">
                <div class="progress-bar bg-info" style="width: 90%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Apple-style Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="apple-card p-4">
            <h5 class="mb-4 fw-semibold">Быстрые действия</h5>
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <a href="index.php?page=process&action=calculation" class="apple-action-card text-decoration-none">
                        <div class="d-flex align-items-center p-3">
                            <div class="action-icon bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Калькуляция</h6>
                                <small class="text-secondary">Расчет стоимости работ</small>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <a href="index.php?page=process&action=client_booking" class="apple-action-card text-decoration-none">
                        <div class="d-flex align-items-center p-3">
                            <div class="action-icon bg-success bg-opacity-10 text-success rounded-3 me-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Запись на ТО</h6>
                                <small class="text-secondary">Бронирование времени</small>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <a href="index.php?page=process&action=work_order" class="apple-action-card text-decoration-none">
                        <div class="d-flex align-items-center p-3">
                            <div class="action-icon bg-warning bg-opacity-10 text-warning rounded-3 me-3">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Заказ-наряд</h6>
                                <small class="text-secondary">Создание задания</small>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <a href="index.php?page=clients" class="apple-action-card text-decoration-none">
                        <div class="d-flex align-items-center p-3">
                            <div class="action-icon bg-info bg-opacity-10 text-info rounded-3 me-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Клиенты</h6>
                                <small class="text-secondary">База клиентов</small>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <a href="index.php?page=vehicles" class="apple-action-card text-decoration-none">
                        <div class="d-flex align-items-center p-3">
                            <div class="action-icon bg-danger bg-opacity-10 text-danger rounded-3 me-3">
                                <i class="fas fa-car"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Автопарк</h6>
                                <small class="text-secondary">Автомобили клиентов</small>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <a href="index.php?page=inventory" class="apple-action-card text-decoration-none">
                        <div class="d-flex align-items-center p-3">
                            <div class="action-icon bg-secondary bg-opacity-10 text-secondary rounded-3 me-3">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Склад</h6>
                                <small class="text-secondary">Запчасти и материалы</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apple-style Data Tables -->
<div class="row">
    <!-- Активные заказы -->
    <div class="col-lg-6 mb-4">
        <div class="apple-card">
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-clock me-2 text-primary"></i>Активные заказы
                </h6>
                <a href="index.php?page=orders" class="btn btn-apple-secondary btn-sm">
                    Все заказы
                </a>
            </div>
            <div class="p-3">
                <?php if (empty($activeOrders)): ?>
                    <div class="text-center py-5">
                        <div class="text-secondary mb-3">
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                        <h6 class="text-secondary mb-2">Нет активных заказов</h6>
                        <p class="text-secondary small mb-0">Все заказы завершены</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($activeOrders as $order): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <strong class="text-primary me-2"><?= $order['order_number'] ?></strong>
                                            <?php
                                            $statusMap = [
                                                'new' => ['class' => 'bg-primary', 'text' => 'Новый'],
                                                'in_progress' => ['class' => 'bg-warning', 'text' => 'В работе'],
                                                'completed' => ['class' => 'bg-success', 'text' => 'Готово'],
                                                'cancelled' => ['class' => 'bg-secondary', 'text' => 'Отменен']
                                            ];
                                            $status = $order['status'] ?? 'new';
                                            $statusClass = $statusMap[$status]['class'] ?? 'bg-secondary';
                                            $statusText = $statusMap[$status]['text'] ?? ucfirst($status);
                                            ?>
                                            <span class="badge <?= $statusClass ?> rounded-pill small"><?= $statusText ?></span>
                                        </div>
                                        <div class="small text-secondary mb-1"><?= $order['client_name'] ?></div>
                                        <div class="small text-secondary"><?= $order['brand'] ?> <?= $order['model'] ?> • <?= $order['license_plate'] ?></div>
                                    </div>
                                    <a href="index.php?page=orders&id=<?= $order['id'] ?>" class="btn btn-apple-secondary btn-sm">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Последние заказы -->
    <div class="col-lg-6 mb-4">
        <div class="apple-card">
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-history me-2 text-success"></i>Последние заказы
                </h6>
                <a href="index.php?page=orders" class="btn btn-apple-secondary btn-sm">
                    Все заказы
                </a>
            </div>
            <div class="p-3">
                <?php if (empty($recentOrders)): ?>
                    <div class="text-center py-5">
                        <div class="text-secondary mb-3">
                            <i class="fas fa-history fa-3x opacity-50"></i>
                        </div>
                        <h6 class="text-secondary mb-2">Нет заказов</h6>
                        <p class="text-secondary small mb-0">Заказы появятся здесь после создания</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($recentOrders, 0, 8) as $order): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <strong class="text-primary me-2"><?= $order['order_number'] ?></strong>
                                            <span class="badge bg-success rounded-pill small">
                                                <?= number_format($order['total_amount'], 0, ',', ' ') ?> ₽
                                            </span>
                                        </div>
                                        <div class="small text-secondary mb-1"><?= $order['client_name'] ?></div>
                                        <div class="small text-secondary">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                                        </div>
                                    </div>
                                    <a href="index.php?page=orders&id=<?= $order['id'] ?>" class="btn btn-apple-secondary btn-sm">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>

<!-- Apple-style Summary Section -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="apple-card p-4 text-center">
            <div class="mb-3">
                <i class="fas fa-ruble-sign fa-3x text-info mb-3"></i>
            </div>
            <h3 class="text-info mb-2"><?= number_format($stats['monthRevenue'], 0, ',', ' ') ?> ₽</h3>
            <p class="text-secondary mb-0">Выручка за месяц</p>
            <div class="progress bg-light mt-3" style="height: 4px;">
                <div class="progress-bar bg-info" style="width: 85%"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="apple-card p-4 text-center">
            <div class="mb-3">
                <i class="fas fa-tasks fa-3x text-warning mb-3"></i>
            </div>
            <h3 class="text-warning mb-2"><?= $stats['inProgressOrders'] ?></h3>
            <p class="text-secondary mb-0">Заказов в работе</p>
            <div class="progress bg-light mt-3" style="height: 4px;">
                <div class="progress-bar bg-warning" style="width: <?= min(($stats['inProgressOrders'] / max($stats['totalOrders'] ?? 1, 1)) * 100, 100) ?>%"></div>
            </div>
        </div>
    </div>
</div>

<style>
/* Apple-style Action Cards */
.apple-action-card {
    background: white;
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius);
    transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    display: block;
}

.apple-action-card:hover {
    transform: scale(1.02);
    box-shadow: var(--apple-shadow);
    border-color: var(--apple-blue);
}

.action-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* List improvements */
.list-group-item:hover {
    background-color: rgba(0, 122, 255, 0.05);
}

/* Button size adjustments */
.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .apple-card {
        margin-bottom: 1rem;
    }
    
    .stat-icon, .action-icon {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
}
</style>

