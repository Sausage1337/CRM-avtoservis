<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-cogs text-primary me-2"></i>
            <span class="text-primary">Управление</span> процессами
        </h2>
        <p class="text-muted mb-0">Система разделенных этапов обслуживания автомобилей</p>
    </div>
    <div>
        <a href="index.php?page=process&action=link_process" class="btn btn-primary me-2">
            <i class="fas fa-link me-2"></i>Связать этапы
        </a>
        <a href="index.php?page=process&action=calculation" class="btn btn-info">
            <i class="fas fa-calculator me-2"></i>Новая калькуляция
        </a>
    </div>
</div>

<!-- Статистика процессов -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center process-card">
            <div class="card-body">
                <div class="process-icon bg-primary">
                    <i class="fas fa-cogs fa-2x text-white"></i>
                </div>
                <h4 class="mb-1 text-primary"><?= $stats['total'] ?></h4>
                <small class="text-muted">Всего процессов</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center process-card">
            <div class="card-body">
                <div class="process-icon bg-success">
                    <i class="fas fa-play-circle fa-2x text-white"></i>
                </div>
                <h4 class="mb-1 text-success"><?= $stats['active'] ?></h4>
                <small class="text-muted">Активных</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center process-card">
            <div class="card-body">
                <div class="process-icon bg-info">
                    <i class="fas fa-calculator fa-2x text-white"></i>
                </div>
                <h4 class="mb-1 text-info"><?= $stats['calculations'] ?></h4>
                <small class="text-muted">Калькуляций</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center process-card">
            <div class="card-body">
                <div class="process-icon bg-success">
                    <i class="fas fa-calendar-check fa-2x text-white"></i>
                </div>
                <h4 class="mb-1 text-success"><?= $stats['bookings'] ?></h4>
                <small class="text-muted">Записей</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center process-card">
            <div class="card-body">
                <div class="process-icon bg-warning">
                    <i class="fas fa-clipboard-list fa-2x text-white"></i>
                </div>
                <h4 class="mb-1 text-warning"><?= $stats['workOrders'] ?></h4>
                <small class="text-muted">Заказ-нарядов</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center process-card">
            <div class="card-body">
                <div class="process-icon bg-success">
                    <i class="fas fa-check-circle fa-2x text-white"></i>
                </div>
                <h4 class="mb-1 text-success"><?= $stats['completed'] ?></h4>
                <small class="text-muted">Завершено</small>
            </div>
        </div>
    </div>
</div>

<!-- Быстрые действия -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Быстрые действия
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <a href="index.php?page=process&action=calculation" class="text-decoration-none">
                            <div class="action-card bg-info bg-opacity-10 border border-info">
                                <div class="action-icon bg-info">
                                    <i class="fas fa-calculator fa-3x text-white"></i>
                                </div>
                                <h6 class="mt-3">Калькуляция услуг</h6>
                                <small class="text-muted">Создать расчет стоимости</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <a href="index.php?page=process&action=client_booking" class="text-decoration-none">
                            <div class="action-card bg-success bg-opacity-10 border border-success">
                                <div class="action-icon bg-success">
                                    <i class="fas fa-calendar-plus fa-3x text-white"></i>
                                </div>
                                <h6 class="mt-3">Запись клиента</h6>
                                <small class="text-muted">Забронировать время</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <a href="index.php?page=process&action=work_order" class="text-decoration-none">
                            <div class="action-card bg-warning bg-opacity-10 border border-warning">
                                <div class="action-icon bg-warning">
                                    <i class="fas fa-clipboard-list fa-3x text-white"></i>
                                </div>
                                <h6 class="mt-3">Заказ-наряд</h6>
                                <small class="text-muted">Создать задание</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <a href="index.php?page=process&action=link_process" class="text-decoration-none">
                            <div class="action-card bg-primary bg-opacity-10 border border-primary">
                                <div class="action-icon bg-primary">
                                    <i class="fas fa-link fa-3x text-white"></i>
                                </div>
                                <h6 class="mt-3">Связать этапы</h6>
                                <small class="text-muted">Объединить процессы</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Активные процессы -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-play-circle me-2"></i>Активные процессы
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($activeProcesses)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-hashtag text-primary me-1"></i>ID</th>
                                    <th><i class="fas fa-calculator text-info me-1"></i>Калькуляция</th>
                                    <th><i class="fas fa-user text-success me-1"></i>Клиент</th>
                                    <th><i class="fas fa-car text-warning me-1"></i>Автомобиль</th>
                                    <th><i class="fas fa-info-circle text-primary me-1"></i>Статус</th>
                                    <th><i class="fas fa-clock text-secondary me-1"></i>Обновлен</th>
                                    <th><i class="fas fa-cogs text-primary me-1"></i>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activeProcesses as $process): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary rounded-pill">#<?= $process['id'] ?></span>
                                    </td>
                                    <td>
                                        <?php if ($process['calculation_desc']): ?>
                                            <i class="fas fa-calculator text-info me-1"></i>
                                            <?= htmlspecialchars($process['calculation_desc']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($process['client_name']): ?>
                                            <i class="fas fa-user text-success me-1"></i>
                                            <?= htmlspecialchars($process['client_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($process['license_plate']): ?>
                                            <i class="fas fa-car text-warning me-1"></i>
                                            <?= htmlspecialchars($process['license_plate']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success rounded-pill">
                                            <i class="fas fa-play me-1"></i>Активен
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('d.m.Y H:i', strtotime($process['updated_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <a href="index.php?page=process&action=view_process&id=<?= $process['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <div class="empty-state">
                            <i class="fas fa-play-circle fa-4x text-muted mb-3"></i>
                            <h5>Активных процессов пока нет</h5>
                            <p class="text-muted">Создайте первый процесс, используя быстрые действия выше</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Последние процессы -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Последние процессы
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentProcesses)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-hashtag text-secondary me-1"></i>ID</th>
                                    <th><i class="fas fa-calculator text-info me-1"></i>Калькуляция</th>
                                    <th><i class="fas fa-user text-success me-1"></i>Клиент</th>
                                    <th><i class="fas fa-car text-warning me-1"></i>Автомобиль</th>
                                    <th><i class="fas fa-info-circle text-primary me-1"></i>Статус</th>
                                    <th><i class="fas fa-calendar text-secondary me-1"></i>Создан</th>
                                    <th><i class="fas fa-cogs text-primary me-1"></i>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentProcesses as $process): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary rounded-pill">#<?= $process['id'] ?></span>
                                    </td>
                                    <td>
                                        <?php if ($process['calculation_desc']): ?>
                                            <i class="fas fa-calculator text-info me-1"></i>
                                            <?= htmlspecialchars($process['calculation_desc']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($process['client_name']): ?>
                                            <i class="fas fa-user text-success me-1"></i>
                                            <?= htmlspecialchars($process['client_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($process['license_plate']): ?>
                                            <i class="fas fa-car text-warning me-1"></i>
                                            <?= htmlspecialchars($process['license_plate']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $process['status'] === 'active' ? 'success' : 
                                                               ($process['status'] === 'completed' ? 'info' : 'secondary') ?> rounded-pill">
                                            <?php if ($process['status'] === 'active'): ?>
                                                <i class="fas fa-play me-1"></i>Активен
                                            <?php elseif ($process['status'] === 'completed'): ?>
                                                <i class="fas fa-check me-1"></i>Завершен
                                            <?php else: ?>
                                                <i class="fas fa-pause me-1"></i>Отменен
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?= date('d.m.Y H:i', strtotime($process['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <a href="index.php?page=process&action=view_process&id=<?= $process['id'] ?>" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <div class="empty-state">
                            <i class="fas fa-history fa-4x text-muted mb-3"></i>
                            <h5>Процессы пока не созданы</h5>
                            <p class="text-muted">Начните с создания калькуляции или записи клиента</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Информация о системе процессов -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>О системе процессов
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="process-info-card">
                            <h6 class="text-info">
                                <i class="fas fa-calculator me-2"></i>Этап 1: Калькуляция
                            </h6>
                            <p class="small text-muted">
                                Создайте расчет стоимости услуг. Выберите услуги, укажите количество и цены. 
                                Система автоматически подсчитает общую сумму.
                            </p>
                            
                            <h6 class="text-success">
                                <i class="fas fa-calendar-plus me-2"></i>Этап 2: Запись клиента
                            </h6>
                            <p class="small text-muted">
                                Забронируйте время для клиента. Выберите клиента, автомобиль, дату и время. 
                                Можно указать требуемые услуги или оставить для уточнения.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="process-info-card">
                            <h6 class="text-warning">
                                <i class="fas fa-clipboard-list me-2"></i>Этап 3: Заказ-наряд
                            </h6>
                            <p class="small text-muted">
                                Создайте финальный документ для выполнения работ. Свяжите с калькуляцией и записью, 
                                укажите приоритет и время выполнения.
                            </p>
                            
                            <h6 class="text-primary">
                                <i class="fas fa-link me-2"></i>Связывание этапов
                            </h6>
                            <p class="small text-muted">
                                Объедините различные этапы в единый процесс. Этапы могут быть автономными, 
                                но связанными для полного контроля над работой.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4 mb-0 border-0 bg-info bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-lightbulb fa-2x text-info me-3"></i>
                        <div>
                            <h6 class="mb-1 text-info">Совет по работе с системой</h6>
                            <p class="mb-0">Начните с создания калькуляции, затем записи клиента, 
                            и завершите заказ-нарядом. Используйте связывание для объединения этапов в единый процесс.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для карточек процессов */
.process-card {
    transition: transform 0.2s ease-in-out;
    border-radius: 15px;
}

.process-card:hover {
    transform: translateY(-5px);
}

.process-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Стили для карточек действий */
.action-card {
    padding: 25px 15px;
    border-radius: 15px;
    transition: all 0.3s ease;
    height: 100%;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.action-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Градиентные заголовки */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
}

/* Стили для таблиц */
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

/* Стили для пустых состояний */
.empty-state {
    padding: 40px 20px;
}

.empty-state i {
    opacity: 0.5;
}

/* Стили для информационных карточек */
.process-info-card {
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    margin-bottom: 20px;
}

.process-info-card h6 {
    font-weight: 600;
    margin-bottom: 10px;
}

/* Адаптивность */
@media (max-width: 768px) {
    .process-card {
        margin-bottom: 15px;
    }
    
    .action-card {
        margin-bottom: 20px;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
}
</style>
