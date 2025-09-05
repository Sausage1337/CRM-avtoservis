<div class="container-fluid">
    <!-- Заголовок страницы -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="view-client-header">
                <div class="d-flex align-items-center">
                    <div class="view-icon me-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h1 class="view-title mb-1">
                            <span class="text-info">Профиль</span> клиента
                        </h1>
                        <p class="view-subtitle mb-0">
                            <i class="fas fa-user me-2"></i><?= htmlspecialchars($client['full_name']) ?>
                        </p>
                    </div>
                </div>
                <div class="view-actions">
                    <a href="index.php?page=clients&action=edit&id=<?= $client['id'] ?>" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Редактировать
                    </a>
                    <a href="index.php?page=clients" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Назад к списку
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Основная информация о клиенте -->
        <div class="col-lg-4 mb-4">
            <div class="client-profile-card">
                <div class="client-profile-header">
                    <div class="client-avatar">
                        <i class="fas fa-user fa-3x"></i>
                    </div>
                    <h5 class="client-name mb-2"><?= htmlspecialchars($client['full_name']) ?></h5>
                    <span class="badge bg-success rounded-pill">
                        <i class="fas fa-check-circle me-1"></i>Активен
                    </span>
                </div>
                <div class="client-profile-body">
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone text-primary"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Телефон</label>
                            <a href="tel:<?= htmlspecialchars($client['phone']) ?>" class="info-value">
                                <?= htmlspecialchars($client['phone']) ?>
                            </a>
                        </div>
                    </div>

                    <?php if ($client['email']): ?>
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope text-info"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Email</label>
                            <a href="mailto:<?= htmlspecialchars($client['email']) ?>" class="info-value">
                                <?= htmlspecialchars($client['email']) ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($client['address']): ?>
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Адрес</label>
                            <span class="info-value"><?= htmlspecialchars($client['address']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($client['birth_date']): ?>
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-birthday-cake text-warning"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Дата рождения</label>
                            <span class="info-value">
                                <?= date('d.m.Y', strtotime($client['birth_date'])) ?>
                                (<?= date_diff(new DateTime($client['birth_date']), new DateTime())->y ?> лет)
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar text-success"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Дата регистрации</label>
                            <span class="info-value">
                                <?= date('d.m.Y H:i', strtotime($client['created_at'])) ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($client['updated_at']): ?>
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock text-secondary"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Последнее обновление</label>
                            <span class="info-value">
                                <?= date('d.m.Y H:i', strtotime($client['updated_at'])) ?>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($client['notes']): ?>
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-sticky-note text-secondary"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Заметки</label>
                            <span class="info-value"><?= htmlspecialchars($client['notes']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="quick-actions-card mt-3">
                <div class="quick-actions-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Быстрые действия
                    </h6>
                </div>
                <div class="quick-actions-body">
                    <div class="quick-action-item">
                        <a href="index.php?page=vehicles&action=create&client_id=<?= $client['id'] ?>" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-car me-2"></i>Добавить автомобиль
                        </a>
                    </div>
                    <div class="quick-action-item">
                        <a href="index.php?page=process&action=client_booking&client_id=<?= $client['id'] ?>" class="btn btn-outline-success w-100 mb-2">
                            <i class="fas fa-calendar-plus me-2"></i>Записать на обслуживание
                        </a>
                    </div>
                    <div class="quick-action-item">
                        <a href="index.php?page=process&action=calculation&client_id=<?= $client['id'] ?>" class="btn btn-outline-info w-100 mb-2">
                            <i class="fas fa-calculator me-2"></i>Создать калькуляцию
                        </a>
                    </div>
                    <div class="quick-action-item">
                        <a href="index.php?page=clients&action=edit&id=<?= $client['id'] ?>" class="btn btn-outline-warning w-100">
                            <i class="fas fa-edit me-2"></i>Редактировать профиль
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Автомобили клиента -->
        <div class="col-lg-4 mb-4">
            <div class="vehicles-card">
                <div class="vehicles-header">
                    <h6 class="mb-0">
                        <i class="fas fa-car text-primary me-2"></i>Автомобили клиента
                    </h6>
                    <span class="vehicles-count"><?= count($vehicles) ?></span>
                </div>
                <div class="vehicles-body">
                    <?php if (empty($vehicles)): ?>
                        <div class="empty-state">
                            <i class="fas fa-car fa-2x mb-2 text-muted"></i>
                            <p class="text-muted mb-2">У клиента нет автомобилей</p>
                            <a href="index.php?page=vehicles&action=create&client_id=<?= $client['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus me-1"></i>Добавить автомобиль
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($vehicles as $vehicle): ?>
                        <div class="vehicle-item">
                            <div class="vehicle-icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="vehicle-info">
                                <div class="vehicle-brand">
                                    <?= htmlspecialchars($vehicle['brand']) ?> <?= htmlspecialchars($vehicle['model']) ?>
                                </div>
                                <div class="vehicle-details">
                                    <span class="license-plate"><?= htmlspecialchars($vehicle['license_plate']) ?></span>
                                    <?php if ($vehicle['year']): ?>
                                        <span class="vehicle-year"><?= $vehicle['year'] ?> г.</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($vehicle['vin']): ?>
                                <div class="vehicle-vin">
                                    <small class="text-muted">VIN: <?= htmlspecialchars($vehicle['vin']) ?></small>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="vehicle-actions">
                                <a href="index.php?page=vehicles&action=view&id=<?= $vehicle['id'] ?>" 
                                   class="btn btn-sm btn-outline-info" title="Просмотр">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Заказы клиента -->
        <div class="col-lg-4 mb-4">
            <div class="orders-card">
                <div class="orders-header">
                    <h6 class="mb-0">
                        <i class="fas fa-clipboard-list text-success me-2"></i>История заказов
                    </h6>
                    <span class="orders-count"><?= count($orders) ?></span>
                </div>
                <div class="orders-body">
                    <?php if (empty($orders)): ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list fa-2x mb-2 text-muted"></i>
                            <p class="text-muted mb-2">У клиента нет заказов</p>
                            <a href="index.php?page=process&action=client_booking&client_id=<?= $client['id'] ?>" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-plus me-1"></i>Создать заказ
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                        <div class="order-item">
                            <div class="order-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="order-info">
                                <div class="order-number">
                                    <a href="index.php?page=orders&id=<?= $order['id'] ?>" class="order-link">
                                        <?= htmlspecialchars($order['order_number']) ?>
                                    </a>
                                </div>
                                <div class="order-details">
                                    <?php if ($order['brand'] && $order['model']): ?>
                                    <span class="vehicle-info">
                                        <?= htmlspecialchars($order['brand']) ?> <?= htmlspecialchars($order['model']) ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php if ($order['license_plate']): ?>
                                    <span class="license-plate"><?= htmlspecialchars($order['license_plate']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="order-status">
                                    <?php
                                    $statusClass = match($order['status']) {
                                        'new' => 'badge bg-primary',
                                        'in_progress' => 'badge bg-warning',
                                        'completed' => 'badge bg-success',
                                        'cancelled' => 'badge bg-danger',
                                        default => 'badge bg-secondary'
                                    };
                                    $statusText = match($order['status']) {
                                        'new' => 'Новый',
                                        'in_progress' => 'В работе',
                                        'completed' => 'Завершен',
                                        'cancelled' => 'Отменен',
                                        default => 'Неизвестно'
                                    };
                                    ?>
                                    <span class="<?= $statusClass ?> rounded-pill">
                                        <?= $statusText ?>
                                    </span>
                                </div>
                                <div class="order-date">
                                    <small class="text-muted">
                                        <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($orders) > 5): ?>
                        <div class="view-all-orders">
                            <a href="index.php?page=orders&client_id=<?= $client['id'] ?>" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="fas fa-external-link-alt me-1"></i>Посмотреть все заказы
                            </a>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика клиента -->
    <div class="row">
        <div class="col-12">
            <div class="client-stats-card">
                <div class="client-stats-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar text-info me-2"></i>Статистика клиента
                    </h6>
                </div>
                <div class="client-stats-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-item">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="stat-number"><?= count($vehicles) ?></div>
                                <div class="stat-label">Автомобилей</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-item">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="stat-number"><?= count($orders) ?></div>
                                <div class="stat-label">Заказов</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-item">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-number">
                                    <?= count(array_filter($orders, fn($o) => $o['status'] === 'in_progress')) ?>
                                </div>
                                <div class="stat-label">В работе</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-item">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-ruble-sign"></i>
                                </div>
                                <div class="stat-number">
                                    <?= number_format(array_sum(array_column($orders, 'total_amount')), 0, ',', ' ') ?> ₽
                                </div>
                                <div class="stat-label">Общая сумма</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для просмотра профиля клиента */
.view-client-header {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.view-icon {
    background: rgba(255,255,255,0.2);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.view-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.view-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.view-actions .btn {
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
}

/* Карточка профиля клиента */
.client-profile-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: none;
}

.client-profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.client-avatar {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.client-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.client-profile-body {
    padding: 1.5rem;
}

.profile-info-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.profile-info-item:last-child {
    border-bottom: none;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-label {
    display: block;
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.info-value {
    display: block;
    font-weight: 500;
    color: #495057;
    text-decoration: none;
}

.info-value:hover {
    color: #667eea;
}

/* Карточка автомобилей */
.vehicles-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: none;
}

.vehicles-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.vehicles-header h6 {
    margin: 0;
    font-weight: 600;
}

.vehicles-count {
    background: rgba(255,255,255,0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
}

.vehicles-body {
    padding: 1.5rem;
}

.vehicle-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #f8f9fa;
    border-radius: 10px;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.vehicle-item:hover {
    border-color: #007bff;
    box-shadow: 0 2px 10px rgba(0,123,255,0.1);
}

.vehicle-item:last-child {
    margin-bottom: 0;
}

.vehicle-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: #007bff;
}

.vehicle-info {
    flex: 1;
}

.vehicle-brand {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
}

.vehicle-details {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.25rem;
}

.license-plate {
    background: #e9ecef;
    padding: 0.25rem 0.5rem;
    border-radius: 5px;
    font-family: monospace;
    font-weight: 600;
    color: #495057;
}

.vehicle-year {
    color: #6c757d;
    font-size: 0.9rem;
}

.vehicle-vin {
    font-size: 0.8rem;
}

.vehicle-actions {
    margin-left: 1rem;
}

/* Карточка заказов */
.orders-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: none;
}

.orders-header {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.orders-header h6 {
    margin: 0;
    font-weight: 600;
}

.orders-count {
    background: rgba(255,255,255,0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
}

.orders-body {
    padding: 1.5rem;
}

.order-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    border: 1px solid #f8f9fa;
    border-radius: 10px;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.order-item:hover {
    border-color: #28a745;
    box-shadow: 0 2px 10px rgba(40,167,69,0.1);
}

.order-item:last-child {
    margin-bottom: 0;
}

.order-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: #28a745;
    flex-shrink: 0;
}

.order-info {
    flex: 1;
}

.order-number {
    margin-bottom: 0.5rem;
}

.order-link {
    color: #495057;
    text-decoration: none;
    font-weight: 600;
}

.order-link:hover {
    color: #28a745;
    text-decoration: underline;
}

.order-details {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.order-status {
    margin-bottom: 0.5rem;
}

.order-date {
    font-size: 0.8rem;
}

.view-all-orders {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f8f9fa;
}

/* Карточка статистики */
.client-stats-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: none;
}

.client-stats-header {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: white;
    padding: 1.5rem;
}

.client-stats-header h6 {
    margin: 0;
    font-weight: 600;
}

.client-stats-body {
    padding: 2rem;
}

.stat-item {
    padding: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Пустые состояния */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-state i {
    opacity: 0.3;
    margin-bottom: 1rem;
}

.empty-state p {
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

/* Карточка быстрых действий */
.quick-actions-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.quick-actions-header {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    color: white;
    padding: 1.5rem;
}

.quick-actions-header h6 {
    margin: 0;
    font-weight: 600;
}

.quick-actions-body {
    padding: 1.5rem;
}

.quick-action-item .btn {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.quick-action-item .btn:hover {
    transform: translateY(-2px);
}

/* Адаптивность */
@media (max-width: 768px) {
    .view-client-header {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    
    .view-actions {
        margin-top: 1rem;
    }
    
    .view-title {
        font-size: 2rem;
    }
    
    .client-profile-body, .vehicles-body, .orders-body, .client-stats-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .view-client-header {
        padding: 1rem;
    }
    
    .view-title {
        font-size: 1.5rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .vehicle-item, .order-item {
        flex-direction: column;
        text-align: center;
    }
    
    .vehicle-icon, .order-icon {
        margin: 0 0 1rem 0;
    }
    
    .vehicle-actions {
        margin: 1rem 0 0 0;
    }
}
</style>
