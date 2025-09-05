<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-project-diagram text-primary me-2"></i>
            <span class="text-primary">Процесс</span> #<?= $process['id'] ?>
        </h2>
        <p class="text-muted mb-0">Детальная информация о процессе обслуживания</p>
    </div>
    <div>
        <a href="index.php?page=process" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Назад к процессам
        </a>
        <a href="index.php?page=process&action=link_process" class="btn btn-outline-primary">
            <i class="fas fa-link me-2"></i>Связать этапы
        </a>
    </div>
</div>

<!-- Основная информация о процессе -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Общая информация
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="process-info-item mb-3">
                            <label class="form-label text-muted small mb-1">
                                <i class="fas fa-calendar me-1"></i>Дата создания
                            </label>
                            <p class="mb-0 fw-bold">
                                <?= date('d.m.Y H:i', strtotime($process['created_at'])) ?>
                            </p>
                        </div>
                        <div class="process-info-item mb-3">
                            <label class="form-label text-muted small mb-1">
                                <i class="fas fa-clock me-1"></i>Последнее обновление
                            </label>
                            <p class="mb-0 fw-bold">
                                <?= date('d.m.Y H:i', strtotime($process['updated_at'])) ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="process-info-item mb-3">
                            <label class="form-label text-muted small mb-1">
                                <i class="fas fa-tasks me-1"></i>Общий статус
                            </label>
                            <div>
                                <span class="badge bg-<?= $process['status'] === 'completed' ? 'success' : 
                                                       ($process['status'] === 'in_progress' ? 'info' : 'warning') ?> rounded-pill fs-6">
                                    <?= $process['status'] === 'completed' ? 'Завершен' : 
                                       ($process['status'] === 'in_progress' ? 'В работе' : 'Активен') ?>
                                </span>
                            </div>
                        </div>
                        <div class="process-info-item mb-3">
                            <label class="form-label text-muted small mb-1">
                                <i class="fas fa-user me-1"></i>Создатель
                            </label>
                            <p class="mb-0 fw-bold">
                                <?= htmlspecialchars($process['created_by_name'] ?? 'Система') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie text-primary me-2"></i>Прогресс
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="progress-circle mb-3">
                    <div class="progress-circle-inner">
                        <span class="progress-percentage">
                            <?php
                            $totalStages = 3;
                            $completedStages = 0;
                            if ($calculation && $calculation['status'] === 'ready') $completedStages++;
                            if ($booking && $booking['status'] === 'confirmed') $completedStages++;
                            if ($workOrder && $workOrder['status'] === 'completed') $completedStages++;
                            $percentage = round(($completedStages / $totalStages) * 100);
                            ?>
                            <?= $percentage ?>%
                        </span>
                    </div>
                </div>
                <p class="text-muted small mb-0">
                    Завершено этапов: <?= $completedStages ?> из <?= $totalStages ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Этапы процесса -->
<div class="row">
    <!-- Калькуляция -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-gradient-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-calculator me-2"></i>Калькуляция
                </h6>
            </div>
            <div class="card-body">
                <?php if ($calculation): ?>
                    <div class="stage-content">
                        <div class="stage-status mb-3">
                            <span class="badge bg-<?= $calculation['status'] === 'ready' ? 'success' : 
                                                   ($calculation['status'] === 'draft' ? 'warning' : 'secondary') ?> rounded-pill">
                                <?= $calculation['status'] === 'ready' ? 'Готово' : 
                                   ($calculation['status'] === 'draft' ? 'Черновик' : 'Использовано') ?>
                            </span>
                        </div>
                        <div class="stage-details">
                            <p class="mb-2">
                                <strong>Описание:</strong><br>
                                <span class="text-muted">
                                    <?= htmlspecialchars($calculation['description'] ?: 'Без описания') ?>
                                </span>
                            </p>
                            <p class="mb-2">
                                <strong>Сумма:</strong><br>
                                <span class="text-primary fw-bold h5 mb-0">
                                    <?= number_format($calculation['total_amount'], 0, ',', ' ') ?> ₽
                                </span>
                            </p>
                            <p class="mb-0">
                                <strong>Создано:</strong><br>
                                <small class="text-muted">
                                    <?= date('d.m.Y H:i', strtotime($calculation['created_at'])) ?>
                                </small>
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="stage-empty text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-calculator fa-2x mb-2 text-muted"></i>
                            <p class="text-muted small mb-0">Калькуляция не связана</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Запись клиента -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-gradient-success text-white">
                <h6 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>Запись клиента
                </h6>
            </div>
            <div class="card-body">
                <?php if ($booking): ?>
                    <div class="stage-content">
                        <div class="stage-status mb-3">
                            <span class="badge bg-<?= $booking['status'] === 'confirmed' ? 'success' : 
                                                   ($booking['status'] === 'pending' ? 'warning' : 'info') ?> rounded-pill">
                                <?= $booking['status'] === 'confirmed' ? 'Подтверждено' : 
                                   ($booking['status'] === 'pending' ? 'Ожидает' : 'Новое') ?>
                            </span>
                        </div>
                        <div class="stage-details">
                            <p class="mb-2">
                                <strong>Клиент:</strong><br>
                                <span class="text-success fw-bold">
                                    <?= htmlspecialchars($booking['client_name']) ?>
                                </span>
                            </p>
                            <p class="mb-2">
                                <strong>Автомобиль:</strong><br>
                                <span class="text-muted">
                                    <?= htmlspecialchars($booking['vehicle_info']) ?>
                                </span>
                            </p>
                            <p class="mb-0">
                                <strong>Дата и время:</strong><br>
                                <small class="text-muted">
                                    <?= date('d.m.Y', strtotime($booking['booking_date'])) ?> 
                                    в <?= $booking['booking_time'] ?>
                                </small>
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="stage-empty text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-calendar-check fa-2x mb-2 text-muted"></i>
                            <p class="text-muted small mb-0">Запись не связана</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Заказ-наряд -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-gradient-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Заказ-наряд
                </h6>
            </div>
            <div class="card-body">
                <?php if ($workOrder): ?>
                    <div class="stage-content">
                        <div class="stage-status mb-3">
                            <span class="badge bg-<?= $workOrder['status'] === 'completed' ? 'success' : 
                                                   ($workOrder['status'] === 'in_progress' ? 'info' : 'warning') ?> rounded-pill">
                                <?= $workOrder['status'] === 'completed' ? 'Завершен' : 
                                   ($workOrder['status'] === 'in_progress' ? 'В работе' : 'Новый') ?>
                            </span>
                        </div>
                        <div class="stage-details">
                            <p class="mb-2">
                                <strong>Номер:</strong><br>
                                <span class="text-warning fw-bold">
                                    <?= htmlspecialchars($workOrder['order_number']) ?>
                                </span>
                            </p>
                            <p class="mb-2">
                                <strong>Приоритет:</strong><br>
                                <span class="badge bg-<?= $workOrder['priority'] === 'urgent' ? 'danger' : 
                                                       ($workOrder['priority'] === 'high' ? 'warning' : 'secondary') ?> rounded-pill">
                                    <?= $workOrder['priority'] === 'urgent' ? 'Срочный' : 
                                       ($workOrder['priority'] === 'high' ? 'Высокий' : 'Обычный') ?>
                                </span>
                            </p>
                            <p class="mb-0">
                                <strong>Стоимость:</strong><br>
                                <span class="text-success fw-bold">
                                    <?= number_format($workOrder['total_amount'], 0, ',', ' ') ?> ₽
                                </span>
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="stage-empty text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list fa-2x mb-2 text-muted"></i>
                            <p class="text-muted small mb-0">Заказ-наряд не связан</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- История процесса -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-history text-secondary me-2"></i>История процесса
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="fas fa-play text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Процесс создан</h6>
                            <p class="text-muted small mb-0">
                                <?= date('d.m.Y H:i', strtotime($process['created_at'])) ?> - 
                                Процесс #<?= $process['id'] ?> был создан пользователем 
                                <?= htmlspecialchars($process['created_by_name'] ?? 'Система') ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php if ($calculation): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info">
                            <i class="fas fa-calculator text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Калькуляция связана</h6>
                            <p class="text-muted small mb-0">
                                <?= date('d.m.Y H:i', strtotime($calculation['created_at'])) ?> - 
                                Связана калькуляция #<?= $calculation['id'] ?> на сумму 
                                <?= number_format($calculation['total_amount'], 0, ',', ' ') ?> ₽
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($booking): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Запись клиента связана</h6>
                            <p class="text-muted small mb-0">
                                <?= date('d.m.Y H:i', strtotime($booking['created_at'])) ?> - 
                                Связана запись клиента <?= htmlspecialchars($booking['client_name']) ?> 
                                на <?= date('d.m.Y', strtotime($booking['booking_date'])) ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($workOrder): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Заказ-наряд создан</h6>
                            <p class="text-muted small mb-0">
                                <?= date('d.m.Y H:i', strtotime($workOrder['created_at'])) ?> - 
                                Создан заказ-наряд #<?= $workOrder['order_number'] ?> 
                                с приоритетом "<?= $workOrder['priority'] ?>"
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-secondary">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Последнее обновление</h6>
                            <p class="text-muted small mb-0">
                                <?= date('d.m.Y H:i', strtotime($process['updated_at'])) ?> - 
                                Процесс обновлен
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Быстрые действия -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-bolt text-warning me-2"></i>Быстрые действия
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="index.php?page=process&action=calculation" class="btn btn-outline-info w-100">
                            <i class="fas fa-calculator me-2"></i>Создать калькуляцию
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?page=process&action=client_booking" class="btn btn-outline-success w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Записать клиента
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?page=process&action=work_order" class="btn btn-outline-warning w-100">
                            <i class="fas fa-clipboard-list me-2"></i>Создать заказ-наряд
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?page=process&action=link_process" class="btn btn-outline-primary w-100">
                            <i class="fas fa-link me-2"></i>Связать этапы
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для просмотра процесса */
.process-info-item label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stage-content {
    min-height: 200px;
}

.stage-empty {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state {
    text-align: center;
}

.empty-state i {
    opacity: 0.3;
}

/* Прогресс круг */
.progress-circle {
    width: 120px;
    height: 120px;
    margin: 0 auto;
    position: relative;
}

.progress-circle-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(
        #007bff <?= $percentage ?>%, 
        #e9ecef <?= $percentage ?>% 100%
    );
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.progress-circle-inner::before {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    z-index: 1;
    font-size: 1.5rem;
    font-weight: bold;
    color: #007bff;
}

/* Таймлайн */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.timeline-content h6 {
    color: #495057;
    margin-bottom: 5px;
}

/* Анимации */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Адаптивность */
@media (max-width: 768px) {
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -12px;
        width: 24px;
        height: 24px;
    }
    
    .timeline-marker i {
        font-size: 0.8rem;
    }
    
    .progress-circle {
        width: 100px;
        height: 100px;
    }
    
    .progress-circle-inner::before {
        width: 70px;
        height: 70px;
    }
    
    .progress-percentage {
        font-size: 1.2rem;
    }
}
</style>
