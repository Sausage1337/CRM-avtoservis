<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-link text-primary me-2"></i>
            <span class="text-primary">Связывание</span> этапов
        </h2>
        <p class="text-muted mb-0">Объединение существующих этапов в единый процесс</p>
    </div>
    <a href="index.php?page=process" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Назад к процессам
    </a>
</div>

<!-- Форма связывания -->
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Связать этапы в процесс
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=process&action=link_process" id="linkProcessForm">
                    <!-- Выбор калькуляции -->
                    <div class="mb-4">
                        <label for="calculation_id" class="form-label">
                            <i class="fas fa-calculator text-info me-2"></i>Калькуляция (необязательно)
                        </label>
                        <select class="form-select form-select-lg" id="calculation_id" name="calculation_id">
                            <option value="">Не связывать с калькуляцией</option>
                            <?php foreach ($calculations as $calc): ?>
                            <option value="<?= $calc['id'] ?>" 
                                    data-amount="<?= $calc['total_amount'] ?>"
                                    data-description="<?= htmlspecialchars($calc['description']) ?>"
                                    data-client="<?= $calc['client_id'] ?? '' ?>"
                                    data-vehicle="<?= $calc['vehicle_id'] ?? '' ?>">
                                Калькуляция #<?= $calc['id'] ?> - <?= number_format($calc['total_amount'], 0, ',', ' ') ?> ₽
                                <?php if ($calc['description']): ?>
                                    (<?= htmlspecialchars(substr($calc['description'], 0, 50)) ?>...)
                                <?php endif; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Выберите существующую калькуляцию для связывания
                        </small>
                    </div>

                    <!-- Выбор записи клиента -->
                    <div class="mb-4">
                        <label for="booking_id" class="form-label">
                            <i class="fas fa-calendar-check text-success me-2"></i>Запись клиента (необязательно)
                        </label>
                        <select class="form-select form-select-lg" id="booking_id" name="booking_id">
                            <option value="">Не связывать с записью</option>
                            <?php foreach ($bookings as $booking): ?>
                            <option value="<?= $booking['id'] ?>" 
                                    data-client="<?= $booking['client_id'] ?>"
                                    data-vehicle="<?= $booking['vehicle_id'] ?>"
                                    data-date="<?= $booking['booking_date'] ?>"
                                    data-time="<?= $booking['booking_time'] ?>"
                                    data-client-name="<?= htmlspecialchars($booking['client_name']) ?>"
                                    data-vehicle-info="<?= htmlspecialchars($booking['vehicle_info']) ?>">
                                Запись #<?= $booking['id'] ?> - <?= htmlspecialchars($booking['client_name']) ?>
                                (<?= date('d.m.Y', strtotime($booking['booking_date'])) ?> в <?= $booking['booking_time'] ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Выберите существующую запись клиента для связывания
                        </small>
                    </div>

                    <!-- Выбор заказ-наряда -->
                    <div class="mb-4">
                        <label for="work_order_id" class="form-label">
                            <i class="fas fa-clipboard-list text-warning me-2"></i>Заказ-наряд (необязательно)
                        </label>
                        <select class="form-select form-select-lg" id="work_order_id" name="work_order_id">
                            <option value="">Не связывать с заказ-нарядом</option>
                            <?php foreach ($workOrders as $order): ?>
                            <option value="<?= $order['id'] ?>" 
                                    data-client="<?= $order['client_id'] ?>"
                                    data-vehicle="<?= $order['vehicle_id'] ?>"
                                    data-amount="<?= $order['total_amount'] ?>"
                                    data-priority="<?= $order['priority'] ?>"
                                    data-order-number="<?= htmlspecialchars($order['order_number']) ?>">
                                Заказ #<?= $order['order_number'] ?> - <?= htmlspecialchars($order['client_name']) ?>
                                (<?= number_format($order['total_amount'], 0, ',', ' ') ?> ₽)
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Выберите существующий заказ-наряд для связывания
                        </small>
                    </div>

                    <!-- Проверка совместимости -->
                    <div id="compatibilityCheck" class="mb-4" style="display: none;">
                        <div class="compatibility-card bg-light p-3 rounded border">
                            <h6 class="text-info mb-3">
                                <i class="fas fa-check-circle me-2"></i>Проверка совместимости
                            </h6>
                            <div id="compatibilityResults">
                                <!-- Результаты проверки будут загружены динамически -->
                            </div>
                        </div>
                    </div>

                    <!-- Предварительный просмотр -->
                    <div id="processPreview" class="mb-4" style="display: none;">
                        <div class="preview-card bg-primary bg-opacity-10 p-3 rounded border border-primary">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-eye me-2"></i>Предварительный просмотр процесса
                            </h6>
                            <div id="previewContent">
                                <!-- Содержимое предварительного просмотра -->
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                            <i class="fas fa-link me-2"></i>Связать этапы
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Информация о связывании -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>О связывании этапов
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    <strong>Связывание этапов</strong> позволяет объединить существующие элементы в единый процесс.
                </p>
                <ul class="small text-muted mb-0">
                    <li>Выберите один или несколько этапов</li>
                    <li>Система проверит совместимость</li>
                    <li>Автоматически создаст процесс</li>
                    <li>Свяжет все выбранные этапы</li>
                </ul>
            </div>
        </div>

        <!-- Правила связывания -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-rules text-warning me-2"></i>Правила связывания
                </h6>
            </div>
            <div class="card-body">
                <div class="rules-list">
                    <div class="rule-item mb-2">
                        <span class="badge bg-success rounded-pill me-2">✓</span>
                        <small class="text-muted">Клиенты должны совпадать</small>
                    </div>
                    <div class="rule-item mb-2">
                        <span class="badge bg-success rounded-pill me-2">✓</span>
                        <small class="text-muted">Автомобили должны совпадать</small>
                    </div>
                    <div class="rule-item mb-2">
                        <span class="badge bg-success rounded-pill me-2">✓</span>
                        <small class="text-muted">Этапы не должны быть уже связаны</small>
                    </div>
                    <div class="rule-item">
                        <span class="badge bg-success rounded-pill me-2">✓</span>
                        <small class="text-muted">Хотя бы один этап должен быть выбран</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar text-info me-2"></i>Статистика
                </h6>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-primary h4 mb-1">
                            <?= count($calculations) ?>
                        </div>
                        <div class="stat-label text-muted small">
                            Доступных калькуляций
                        </div>
                    </div>
                    <div class="stat-item text-center mb-3">
                        <div class="stat-number text-success h4 mb-1">
                            <?= count($bookings) ?>
                        </div>
                        <div class="stat-label text-muted small">
                            Доступных записей
                        </div>
                    </div>
                    <div class="stat-item text-center">
                        <div class="stat-number text-warning h4 mb-1">
                            <?= count($workOrders) ?>
                        </div>
                        <div class="stat-label text-muted small">
                            Доступных заказ-нарядов
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для связывания процессов */
.compatibility-card {
    border-left: 4px solid #17a2b8;
    transition: all 0.3s ease;
}

.compatibility-card:hover {
    border-left-color: #138496;
    box-shadow: 0 2px 8px rgba(23, 162, 184, 0.1);
}

.preview-card {
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.preview-card:hover {
    border-left-color: #0056b3;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
}

.rules-list .rule-item {
    display: flex;
    align-items: center;
}

.rules-list .badge {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

.stats-grid .stat-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.stats-grid .stat-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.stat-number {
    font-weight: bold;
}

/* Анимации для кнопок */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Стили для форм */
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Стили для совместимости */
.compatibility-success {
    color: #28a745;
    font-weight: bold;
}

.compatibility-warning {
    color: #ffc107;
    font-weight: bold;
}

.compatibility-error {
    color: #dc3545;
    font-weight: bold;
}

/* Адаптивность */
@media (max-width: 768px) {
    .stats-grid .stat-item {
        margin-bottom: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculationSelect = document.getElementById('calculation_id');
    const bookingSelect = document.getElementById('booking_id');
    const workOrderSelect = document.getElementById('work_order_id');
    const compatibilityCheck = document.getElementById('compatibilityCheck');
    const processPreview = document.getElementById('processPreview');
    const submitBtn = document.getElementById('submitBtn');
    const compatibilityResults = document.getElementById('compatibilityResults');
    const previewContent = document.getElementById('previewContent');

    // Функция проверки совместимости
    function checkCompatibility() {
        const selectedCalculation = calculationSelect.value;
        const selectedBooking = bookingSelect.value;
        const selectedWorkOrder = workOrderSelect.value;

        if (!selectedCalculation && !selectedBooking && !selectedWorkOrder) {
            compatibilityCheck.style.display = 'none';
            processPreview.style.display = 'none';
            submitBtn.disabled = true;
            return;
        }

        // Собираем данные выбранных элементов
        const selectedData = {};
        
        if (selectedCalculation) {
            const calcOption = calculationSelect.options[calculationSelect.selectedIndex];
            selectedData.calculation = {
                id: calcOption.value,
                amount: calcOption.dataset.amount,
                description: calcOption.dataset.description,
                client: calcOption.dataset.client,
                vehicle: calcOption.dataset.vehicle
            };
        }
        
        if (selectedBooking) {
            const bookingOption = bookingSelect.options[bookingSelect.selectedIndex];
            selectedData.booking = {
                id: bookingOption.value,
                client: bookingOption.dataset.client,
                vehicle: bookingOption.dataset.vehicle,
                date: bookingOption.dataset.date,
                time: bookingOption.dataset.time,
                clientName: bookingOption.dataset.clientName,
                vehicleInfo: bookingOption.dataset.vehicleInfo
            };
        }
        
        if (selectedWorkOrder) {
            const orderOption = workOrderSelect.options[workOrderSelect.selectedIndex];
            selectedData.workOrder = {
                id: orderOption.value,
                client: orderOption.dataset.client,
                vehicle: orderOption.dataset.vehicle,
                amount: orderOption.dataset.amount,
                priority: orderOption.dataset.priority,
                orderNumber: orderOption.dataset.orderNumber
            };
        }

        // Проверяем совместимость
        const compatibility = validateCompatibility(selectedData);
        displayCompatibilityResults(compatibility);
        displayProcessPreview(selectedData, compatibility);
        
        compatibilityCheck.style.display = 'block';
        processPreview.style.display = 'block';
        submitBtn.disabled = !compatibility.isCompatible;
    }

    // Валидация совместимости
    function validateCompatibility(data) {
        const result = {
            isCompatible: true,
            warnings: [],
            errors: []
        };

        const clients = [];
        const vehicles = [];

        // Собираем клиентов и автомобили
        if (data.calculation) {
            if (data.calculation.client) clients.push(data.calculation.client);
            if (data.calculation.vehicle) vehicles.push(data.calculation.vehicle);
        }
        if (data.booking) {
            clients.push(data.booking.client);
            vehicles.push(data.booking.vehicle);
        }
        if (data.workOrder) {
            clients.push(data.workOrder.client);
            vehicles.push(data.workOrder.vehicle);
        }

        // Проверяем уникальность клиентов
        const uniqueClients = [...new Set(clients)];
        if (uniqueClients.length > 1) {
            result.errors.push('Клиенты в выбранных этапах не совпадают');
            result.isCompatible = false;
        }

        // Проверяем уникальность автомобилей
        const uniqueVehicles = [...new Set(vehicles)];
        if (uniqueVehicles.length > 1) {
            result.errors.push('Автомобили в выбранных этапах не совпадают');
            result.isCompatible = false;
        }

        // Проверяем наличие хотя бы одного этапа
        if (Object.keys(data).length === 0) {
            result.errors.push('Необходимо выбрать хотя бы один этап');
            result.isCompatible = false;
        }

        // Добавляем предупреждения
        if (Object.keys(data).length === 1) {
            result.warnings.push('Рекомендуется связать несколько этапов для полноты процесса');
        }

        return result;
    }

    // Отображение результатов проверки совместимости
    function displayCompatibilityResults(compatibility) {
        let html = '';

        if (compatibility.errors.length > 0) {
            html += '<div class="mb-3">';
            html += '<h6 class="text-danger mb-2"><i class="fas fa-times-circle me-2"></i>Ошибки совместимости:</h6>';
            compatibility.errors.forEach(error => {
                html += `<p class="text-danger small mb-1"><i class="fas fa-exclamation-triangle me-1"></i>${error}</p>`;
            });
            html += '</div>';
        }

        if (compatibility.warnings.length > 0) {
            html += '<div class="mb-3">';
            html += '<h6 class="text-warning mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Предупреждения:</h6>';
            compatibility.warnings.forEach(warning => {
                html += `<p class="text-warning small mb-1"><i class="fas fa-info-circle me-1"></i>${warning}</p>`;
            });
            html += '</div>';
        }

        if (compatibility.isCompatible) {
            html += '<div class="text-success">';
            html += '<h6 class="mb-2"><i class="fas fa-check-circle me-2"></i>Этапы совместимы!</h6>';
            html += '<p class="small mb-0">Можно создать процесс</p>';
            html += '</div>';
        }

        compatibilityResults.innerHTML = html;
    }

    // Отображение предварительного просмотра
    function displayProcessPreview(data, compatibility) {
        let html = '<div class="row">';

        if (data.calculation) {
            html += `
                <div class="col-md-4 mb-3">
                    <div class="card bg-info bg-opacity-10 border-info">
                        <div class="card-body text-center">
                            <i class="fas fa-calculator text-info fa-2x mb-2"></i>
                            <h6 class="text-info">Калькуляция #${data.calculation.id}</h6>
                            <p class="small text-muted mb-1">${data.calculation.description || 'Без описания'}</p>
                            <strong class="text-primary">${data.calculation.amount} ₽</strong>
                        </div>
                    </div>
                </div>
            `;
        }

        if (data.booking) {
            html += `
                <div class="col-md-4 mb-3">
                    <div class="card bg-success bg-opacity-10 border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check text-success fa-2x mb-2"></i>
                            <h6 class="text-success">Запись #${data.booking.id}</h6>
                            <p class="small text-muted mb-1">${data.booking.clientName}</p>
                            <strong class="text-success">${data.booking.date} в ${data.booking.time}</strong>
                        </div>
                    </div>
                </div>
            `;
        }

        if (data.workOrder) {
            html += `
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning bg-opacity-10 border-warning">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-list text-warning fa-2x mb-2"></i>
                            <h6 class="text-warning">Заказ #${data.workOrder.orderNumber}</h6>
                            <p class="small text-muted mb-1">Приоритет: ${data.workOrder.priority}</p>
                            <strong class="text-warning">${data.workOrder.amount} ₽</strong>
                        </div>
                    </div>
                </div>
            `;
        }

        html += '</div>';

        if (compatibility.isCompatible) {
            html += `
                <div class="text-center mt-3">
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Процесс будет создан успешно!</strong>
                    </div>
                </div>
            `;
        }

        previewContent.innerHTML = html;
    }

    // Обработчики изменений
    calculationSelect.addEventListener('change', checkCompatibility);
    bookingSelect.addEventListener('change', checkCompatibility);
    workOrderSelect.addEventListener('change', checkCompatibility);

    // Валидация формы
    document.getElementById('linkProcessForm').addEventListener('submit', function(e) {
        const selectedCalculation = calculationSelect.value;
        const selectedBooking = bookingSelect.value;
        const selectedWorkOrder = workOrderSelect.value;

        if (!selectedCalculation && !selectedBooking && !selectedWorkOrder) {
            e.preventDefault();
            alert('Выберите хотя бы один этап для связывания');
            return false;
        }

        // Дополнительная проверка совместимости перед отправкой
        const data = {};
        if (selectedCalculation) {
            const calcOption = calculationSelect.options[calculationSelect.selectedIndex];
            data.calculation = {
                client: calcOption.dataset.client,
                vehicle: calcOption.dataset.vehicle
            };
        }
        if (selectedBooking) {
            const bookingOption = bookingSelect.options[bookingSelect.selectedIndex];
            data.booking = {
                client: bookingOption.dataset.client,
                vehicle: bookingOption.dataset.vehicle
            };
        }
        if (selectedWorkOrder) {
            const orderOption = workOrderSelect.options[workOrderSelect.selectedIndex];
            data.workOrder = {
                client: orderOption.dataset.client,
                vehicle: orderOption.dataset.vehicle
            };
        }

        const compatibility = validateCompatibility(data);
        if (!compatibility.isCompatible) {
            e.preventDefault();
            alert('Выбранные этапы несовместимы. Проверьте ошибки совместимости.');
            return false;
        }
    });
});
</script>
