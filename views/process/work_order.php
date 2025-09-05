<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-clipboard-list text-warning me-2"></i>
            <span class="text-warning">Заказ-наряд</span>
        </h2>
        <p class="text-muted mb-0">Создание и управление рабочими заданиями для мастеров</p>
    </div>
    <a href="index.php?page=process" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Назад к процессам
    </a>
</div>

<!-- Форма заказ-наряда -->
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Создать новый заказ-наряд
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=process&action=work_order" id="workOrderForm">
                    <!-- Связывание с другими этапами -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="calculation_id" class="form-label">
                                <i class="fas fa-calculator text-info me-2"></i>Связать с калькуляцией
                            </label>
                            <select class="form-select form-select-lg" id="calculation_id" name="calculation_id">
                                <option value="">Не связывать</option>
                                <?php foreach ($calculations as $calc): ?>
                                <option value="<?= $calc['id'] ?>" 
                                        data-amount="<?= $calc['total_amount'] ?>"
                                        data-description="<?= htmlspecialchars($calc['description']) ?>">
                                    Калькуляция #<?= $calc['id'] ?> - <?= number_format($calc['total_amount'], 0, ',', ' ') ?> ₽
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="booking_id" class="form-label">
                                <i class="fas fa-calendar-check text-success me-2"></i>Связать с записью
                            </label>
                            <select class="form-select form-select-lg" id="booking_id" name="booking_id">
                                <option value="">Не связывать</option>
                                <?php foreach ($bookings as $booking): ?>
                                <option value="<?= $booking['id'] ?>" 
                                        data-client="<?= $booking['client_id'] ?>"
                                        data-vehicle="<?= $booking['vehicle_id'] ?>"
                                        data-date="<?= $booking['booking_date'] ?>"
                                        data-time="<?= $booking['booking_time'] ?>">
                                    Запись #<?= $booking['id'] ?> - <?= htmlspecialchars($booking['client_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Информация о связанных этапах -->
                    <div id="linkedInfo" class="mb-4" style="display: none;">
                        <div class="linked-info-card bg-light p-3 rounded border">
                            <h6 class="text-info mb-3">
                                <i class="fas fa-link me-2"></i>Информация о связанных этапах
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="calculationInfo" style="display: none;">
                                        <p class="mb-1">
                                            <strong>Калькуляция:</strong> 
                                            <span id="calcDescription" class="text-muted"></span>
                                        </p>
                                        <p class="mb-0">
                                            <strong>Сумма:</strong> 
                                            <span id="calcAmount" class="text-primary fw-bold"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="bookingInfo" style="display: none;">
                                        <p class="mb-1">
                                            <strong>Запись:</strong> 
                                            <span id="bookingDateTime" class="text-muted"></span>
                                        </p>
                                        <p class="mb-0">
                                            <strong>Клиент:</strong> 
                                            <span id="bookingClient" class="text-success fw-bold"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Выбор клиента и автомобиля -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="client_id" class="form-label">
                                <i class="fas fa-user text-primary me-2"></i>Клиент
                            </label>
                            <select class="form-select form-select-lg" id="client_id" name="client_id" required>
                                <option value="">Выберите клиента</option>
                                <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>">
                                    <?= htmlspecialchars($client['full_name']) ?> 
                                    (<?= htmlspecialchars($client['phone']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="vehicle_id" class="form-label">
                                <i class="fas fa-car text-warning me-2"></i>Автомобиль
                            </label>
                            <select class="form-select form-select-lg" id="vehicle_id" name="vehicle_id" required>
                                <option value="">Сначала выберите клиента</option>
                            </select>
                        </div>
                    </div>

                    <!-- Описание работ -->
                    <div class="mb-4">
                        <label for="work_description" class="form-label">
                            <i class="fas fa-tools text-warning me-2"></i>Описание работ
                        </label>
                        <textarea class="form-control form-control-lg" id="work_description" 
                                  name="work_description" rows="4" 
                                  placeholder="Подробное описание работ, которые необходимо выполнить..." required></textarea>
                    </div>

                    <!-- Приоритет и сроки -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="priority" class="form-label">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>Приоритет
                            </label>
                            <select class="form-select form-select-lg" id="priority" name="priority" required>
                                <option value="normal">Обычный</option>
                                <option value="high">Высокий</option>
                                <option value="urgent">Срочный</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="estimated_duration" class="form-label">
                                <i class="fas fa-clock text-info me-2"></i>Ориентировочная длительность
                            </label>
                            <select class="form-select form-select-lg" id="estimated_duration" name="estimated_duration" required>
                                <option value="1">1 час</option>
                                <option value="2">2 часа</option>
                                <option value="4">4 часа</option>
                                <option value="8">8 часов (1 день)</option>
                                <option value="16">16 часов (2 дня)</option>
                                <option value="24">24 часа (3 дня)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Дополнительная информация -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note text-info me-2"></i>Дополнительные заметки
                        </label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Особые требования, замечания мастера, детали выполнения работ..."></textarea>
                    </div>

                    <!-- Стоимость -->
                    <div class="mb-4">
                        <label for="total_amount" class="form-label">
                            <i class="fas fa-ruble-sign text-success me-2"></i>Общая стоимость
                        </label>
                        <div class="input-group input-group-lg">
                            <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                   step="0.01" placeholder="0.00" required>
                            <span class="input-group-text">₽</span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Сумма будет автоматически заполнена при связывании с калькуляцией
                        </small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning btn-lg text-dark">
                            <i class="fas fa-save me-2"></i>Создать заказ-наряд
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Существующие заказ-наряды -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Заказ-наряды
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($workOrders)): ?>
                    <?php foreach ($workOrders as $order): ?>
                    <div class="work-order-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    <i class="fas fa-clipboard-list text-warning me-1"></i>
                                    Заказ #<?= $order['order_number'] ?>
                                </h6>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-user me-1"></i>
                                    <?= htmlspecialchars($order['client_name']) ?>
                                </p>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-car me-1"></i>
                                    <?= htmlspecialchars($order['vehicle_info']) ?>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?= date('d.m.Y', strtotime($order['created_at'])) ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= $order['priority'] === 'urgent' ? 'danger' : 
                                                       ($order['priority'] === 'high' ? 'warning' : 'secondary') ?> rounded-pill">
                                    <?= $order['priority'] === 'urgent' ? 'Срочный' : 
                                       ($order['priority'] === 'high' ? 'Высокий' : 'Обычный') ?>
                                </span>
                                <div class="mt-2">
                                    <span class="badge bg-<?= $order['status'] === 'completed' ? 'success' : 
                                                           ($order['status'] === 'in_progress' ? 'info' : 'warning') ?> rounded-pill">
                                        <?= $order['status'] === 'completed' ? 'Завершен' : 
                                           ($order['status'] === 'in_progress' ? 'В работе' : 'Новый') ?>
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <strong class="text-success h6 mb-0">
                                        <?= number_format($order['total_amount'], 0, ',', ' ') ?> ₽
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                            <h6>Заказ-наряды пока не созданы</h6>
                            <p class="small">Создайте первый заказ-наряд для выполнения работ</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Информация о заказ-наряде -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle text-warning me-2"></i>О заказ-наряде
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    <strong>Заказ-наряд</strong> - это основной документ для выполнения работ.
                </p>
                <ul class="small text-muted mb-0">
                    <li>Можно связать с калькуляцией и записью</li>
                    <li>Автоматическое заполнение данных клиента</li>
                    <li>Установка приоритета и сроков</li>
                    <li>Отслеживание статуса выполнения</li>
                </ul>
            </div>
        </div>

        <!-- Статусы заказ-нарядов -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-tasks text-info me-2"></i>Статусы выполнения
                </h6>
            </div>
            <div class="card-body">
                <div class="status-info">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning rounded-pill me-2">Новый</span>
                        <small class="text-muted">Создан, ожидает начала работ</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-info rounded-pill me-2">В работе</span>
                        <small class="text-muted">Работы выполняются</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success rounded-pill me-2">Завершен</span>
                        <small class="text-muted">Все работы выполнены</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для заказ-наряда */
.linked-info-card {
    border-left: 4px solid #ffc107;
    transition: all 0.3s ease;
}

.linked-info-card:hover {
    border-left-color: #ffb300;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.1);
}

.work-order-item {
    transition: all 0.3s ease;
}

.work-order-item:hover {
    background-color: rgba(0,0,0,0.02);
    padding-left: 10px;
}

.empty-state {
    padding: 20px;
}

.empty-state i {
    opacity: 0.5;
}

.status-info .badge {
    min-width: 60px;
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
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

/* Стили для приоритетов */
#priority option[value="normal"] {
    color: #6c757d;
}

#priority option[value="high"] {
    color: #ffc107;
}

#priority option[value="urgent"] {
    color: #dc3545;
}

/* Стили для связанной информации */
#linkedInfo {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculationSelect = document.getElementById('calculation_id');
    const bookingSelect = document.getElementById('booking_id');
    const clientSelect = document.getElementById('client_id');
    const vehicleSelect = document.getElementById('vehicle_id');
    const totalAmountInput = document.getElementById('total_amount');
    const linkedInfo = document.getElementById('linkedInfo');
    const calculationInfo = document.getElementById('calculationInfo');
    const bookingInfo = document.getElementById('bookingInfo');

    // Обработчик изменения калькуляции
    calculationSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const amount = selectedOption.dataset.amount;
            const description = selectedOption.dataset.description;
            
            totalAmountInput.value = amount;
            
            // Показываем информацию о калькуляции
            document.getElementById('calcDescription').textContent = description;
            document.getElementById('calcAmount').textContent = amount + ' ₽';
            calculationInfo.style.display = 'block';
            
            showLinkedInfo();
        } else {
            calculationInfo.style.display = 'none';
            hideLinkedInfo();
        }
    });

    // Обработчик изменения записи
    bookingSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const clientId = selectedOption.dataset.client;
            const vehicleId = selectedOption.dataset.vehicle;
            const date = selectedOption.dataset.date;
            const time = selectedOption.dataset.time;
            
            // Автоматически выбираем клиента и автомобиль
            clientSelect.value = clientId;
            loadClientVehicles(clientId);
            
            // Показываем информацию о записи
            document.getElementById('bookingDateTime').textContent = 
                new Date(date).toLocaleDateString('ru-RU') + ' в ' + time;
            document.getElementById('bookingClient').textContent = 
                selectedOption.textContent.split(' - ')[1];
            bookingInfo.style.display = 'block';
            
            showLinkedInfo();
        } else {
            bookingInfo.style.display = 'none';
            hideLinkedInfo();
        }
    });

    // Показать информацию о связанных этапах
    function showLinkedInfo() {
        if (calculationInfo.style.display !== 'none' || bookingInfo.style.display !== 'none') {
            linkedInfo.style.display = 'block';
        }
    }

    // Скрыть информацию о связанных этапах
    function hideLinkedInfo() {
        if (calculationInfo.style.display === 'none' && bookingInfo.style.display === 'none') {
            linkedInfo.style.display = 'none';
        }
    }

    // Загрузка автомобилей клиента
    function loadClientVehicles(clientId) {
        if (!clientId) return;
        
        fetch(`index.php?page=process&action=get_client_vehicles&client_id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.vehicles) {
                    vehicleSelect.innerHTML = '<option value="">Выберите автомобиль</option>';
                    data.vehicles.forEach(vehicle => {
                        const option = document.createElement('option');
                        option.value = vehicle.id;
                        option.textContent = `${vehicle.brand} ${vehicle.model} (${vehicle.license_plate})`;
                        vehicleSelect.appendChild(option);
                    });
                } else {
                    vehicleSelect.innerHTML = '<option value="">У клиента нет автомобилей</option>';
                }
            })
            .catch(error => {
                console.error('Ошибка загрузки автомобилей:', error);
                vehicleSelect.innerHTML = '<option value="">Ошибка загрузки</option>';
            });
    }

    // Обработчик изменения клиента
    clientSelect.addEventListener('change', function() {
        const clientId = this.value;
        if (clientId) {
            loadClientVehicles(clientId);
        } else {
            vehicleSelect.innerHTML = '<option value="">Сначала выберите клиента</option>';
        }
    });

    // Валидация формы
    document.getElementById('workOrderForm').addEventListener('submit', function(e) {
        const clientId = document.getElementById('client_id').value;
        const vehicleId = document.getElementById('vehicle_id').value;
        const workDescription = document.getElementById('work_description').value.trim();
        const totalAmount = document.getElementById('total_amount').value;
        
        if (!clientId) {
            e.preventDefault();
            alert('Выберите клиента');
            return false;
        }
        
        if (!vehicleId) {
            e.preventDefault();
            alert('Выберите автомобиль');
            return false;
        }
        
        if (!workDescription) {
            e.preventDefault();
            alert('Опишите планируемые работы');
            return false;
        }
        
        if (!totalAmount || parseFloat(totalAmount) <= 0) {
            e.preventDefault();
            alert('Укажите корректную стоимость работ');
            return false;
        }
    });

    // Автоматическое заполнение описания работ при выборе калькуляции
    calculationSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const workDescription = document.getElementById('work_description');
        
        if (selectedOption.value && !workDescription.value.trim()) {
            workDescription.value = 'Работы согласно калькуляции #' + selectedOption.value;
        }
    });
});
</script>
