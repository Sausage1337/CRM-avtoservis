<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-calendar-check text-success me-2"></i>
            <span class="text-success">Запись</span> клиентов
        </h2>
        <p class="text-muted mb-0">Планирование и бронирование времени для обслуживания</p>
    </div>
    <a href="index.php?page=process" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Назад к процессам
    </a>
</div>

<!-- Форма записи -->
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Создать новую запись
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=process&action=client_booking" id="bookingForm">
                    <!-- Выбор клиента и автомобиля -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="client_id" class="form-label">
                                <i class="fas fa-user text-primary me-2"></i>Клиент
                            </label>
                            <select class="form-select form-select-lg" id="client_id" name="client_id" required>
                                <option value="">Выберите клиента</option>
                                <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>" 
                                        data-phone="<?= htmlspecialchars($client['phone']) ?>"
                                        data-email="<?= htmlspecialchars($client['email']) ?>">
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

                    <!-- Информация о клиенте -->
                    <div id="clientInfo" class="mb-4" style="display: none;">
                        <div class="client-info-card bg-light p-3 rounded border">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Информация о клиенте
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <strong>Телефон:</strong> 
                                        <span id="clientPhone" class="text-muted"></span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Email:</strong> 
                                        <span id="clientEmail" class="text-muted"></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <strong>Автомобиль:</strong> 
                                        <span id="vehicleInfo" class="text-muted"></span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>VIN:</strong> 
                                        <span id="vehicleVin" class="text-muted"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Дата и время -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="booking_date" class="form-label">
                                <i class="fas fa-calendar text-success me-2"></i>Дата записи
                            </label>
                            <input type="date" class="form-control form-control-lg" id="booking_date" 
                                   name="booking_date" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="booking_time" class="form-label">
                                <i class="fas fa-clock text-success me-2"></i>Время записи
                            </label>
                            <select class="form-select form-select-lg" id="booking_time" name="booking_time" required>
                                <option value="">Выберите время</option>
                                <?php foreach ($timeSlots as $slot): ?>
                                <option value="<?= $slot ?>"><?= $slot ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Описание работ -->
                    <div class="mb-4">
                        <label for="services_description" class="form-label">
                            <i class="fas fa-tools text-warning me-2"></i>Описание работ
                        </label>
                        <textarea class="form-control form-control-lg" id="services_description" 
                                  name="services_description" rows="4" 
                                  placeholder="Опишите планируемые работы, диагностику или обслуживание..." required></textarea>
                    </div>

                    <!-- Дополнительная информация -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note text-info me-2"></i>Дополнительные заметки
                        </label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Особые требования, замечания или дополнительная информация..."></textarea>
                    </div>

                    <!-- Приоритет -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>Приоритет записи
                        </label>
                        <div class="priority-options">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priority_low" value="low" checked>
                                <label class="form-check-label" for="priority_low">
                                    <span class="badge bg-secondary">Обычный</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priority_medium" value="medium">
                                <label class="form-check-label" for="priority_medium">
                                    <span class="badge bg-warning">Средний</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priority_high" value="high">
                                <label class="form-check-label" for="priority_high">
                                    <span class="badge bg-danger">Высокий</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Создать запись
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Активные записи -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Активные записи
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): ?>
                    <div class="booking-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    <i class="fas fa-calendar-check text-success me-1"></i>
                                    Запись #<?= $booking['id'] ?>
                                </h6>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-user me-1"></i>
                                    <?= htmlspecialchars($booking['client_name']) ?>
                                </p>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-car me-1"></i>
                                    <?= htmlspecialchars($booking['vehicle_info']) ?>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?= date('d.m.Y', strtotime($booking['booking_date'])) ?> 
                                    в <?= $booking['booking_time'] ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= $booking['priority'] === 'high' ? 'danger' : 
                                                       ($booking['priority'] === 'medium' ? 'warning' : 'secondary') ?> rounded-pill">
                                    <?= $booking['priority'] === 'high' ? 'Высокий' : 
                                       ($booking['priority'] === 'medium' ? 'Средний' : 'Обычный') ?>
                                </span>
                                <div class="mt-2">
                                    <span class="badge bg-<?= $booking['status'] === 'confirmed' ? 'success' : 
                                                           ($booking['status'] === 'pending' ? 'warning' : 'info') ?> rounded-pill">
                                        <?= $booking['status'] === 'confirmed' ? 'Подтверждено' : 
                                           ($booking['status'] === 'pending' ? 'Ожидает' : 'Новое') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <div class="empty-state">
                            <i class="fas fa-calendar-check fa-3x mb-3"></i>
                            <h6>Записи пока не созданы</h6>
                            <p class="small">Создайте первую запись для планирования обслуживания</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Информация о записи -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle text-success me-2"></i>О записи клиентов
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    <strong>Запись клиента</strong> - это планирование времени для обслуживания.
                </p>
                <ul class="small text-muted mb-0">
                    <li>Выбор удобного времени для клиента</li>
                    <li>Привязка к конкретному автомобилю</li>
                    <li>Описание планируемых работ</li>
                    <li>Установка приоритета обслуживания</li>
                </ul>
            </div>
        </div>

        <!-- Доступное время -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-clock text-success me-2"></i>Рабочее время
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    <strong>График работы:</strong> Пн-Пт: 9:00-18:00
                </p>
                <div class="time-slots-info">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Записи принимаются с интервалом в 1 час
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для записи клиентов */
.client-info-card {
    border-left: 4px solid #28a745;
    transition: all 0.3s ease;
}

.client-info-card:hover {
    border-left-color: #20c997;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.1);
}

.booking-item {
    transition: all 0.3s ease;
}

.booking-item:hover {
    background-color: rgba(0,0,0,0.02);
    padding-left: 10px;
}

.priority-options .form-check-inline {
    margin-right: 1rem;
}

.priority-options .badge {
    font-size: 0.8rem;
    padding: 0.5rem 0.8rem;
}

.empty-state {
    padding: 20px;
}

.empty-state i {
    opacity: 0.5;
}

.time-slots-info {
    padding: 10px;
    background-color: rgba(40, 167, 69, 0.1);
    border-radius: 5px;
    border-left: 3px solid #28a745;
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
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

/* Стили для приоритетов */
#priority_low:checked + label .badge {
    background-color: #6c757d !important;
}

#priority_medium:checked + label .badge {
    background-color: #ffc107 !important;
    color: #000 !important;
}

#priority_high:checked + label .badge {
    background-color: #dc3545 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('client_id');
    const vehicleSelect = document.getElementById('vehicle_id');
    const clientInfo = document.getElementById('clientInfo');
    const clientPhone = document.getElementById('clientPhone');
    const clientEmail = document.getElementById('clientEmail');
    const vehicleInfo = document.getElementById('vehicleInfo');
    const vehicleVin = document.getElementById('vehicleVin');

    // Обработчик изменения клиента
    clientSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const clientId = this.value;
        
        if (clientId) {
            // Показываем информацию о клиенте
            clientPhone.textContent = selectedOption.dataset.phone;
            clientEmail.textContent = selectedOption.dataset.email || 'Не указан';
            clientInfo.style.display = 'block';
            
            // Загружаем автомобили клиента
            loadClientVehicles(clientId);
        } else {
            clientInfo.style.display = 'none';
            vehicleSelect.innerHTML = '<option value="">Сначала выберите клиента</option>';
        }
    });

    // Загрузка автомобилей клиента
    function loadClientVehicles(clientId) {
        fetch(`index.php?page=process&action=get_client_vehicles&client_id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.vehicles) {
                    vehicleSelect.innerHTML = '<option value="">Выберите автомобиль</option>';
                    data.vehicles.forEach(vehicle => {
                        const option = document.createElement('option');
                        option.value = vehicle.id;
                        option.textContent = `${vehicle.brand} ${vehicle.model} (${vehicle.license_plate})`;
                        option.dataset.vin = vehicle.vin;
                        option.dataset.info = `${vehicle.brand} ${vehicle.model} ${vehicle.year}`;
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

    // Обработчик изменения автомобиля
    vehicleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            vehicleInfo.textContent = selectedOption.dataset.info;
            vehicleVin.textContent = selectedOption.dataset.vin || 'Не указан';
        }
    });

    // Валидация формы
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const clientId = document.getElementById('client_id').value;
        const vehicleId = document.getElementById('vehicle_id').value;
        const bookingDate = document.getElementById('booking_date').value;
        const bookingTime = document.getElementById('booking_time').value;
        const servicesDescription = document.getElementById('services_description').value.trim();
        
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
        
        if (!bookingDate) {
            e.preventDefault();
            alert('Выберите дату записи');
            return false;
        }
        
        if (!bookingTime) {
            e.preventDefault();
            alert('Выберите время записи');
            return false;
        }
        
        if (!servicesDescription) {
            e.preventDefault();
            alert('Опишите планируемые работы');
            return false;
        }
        
        // Проверяем, что дата не в прошлом
        const selectedDate = new Date(bookingDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            e.preventDefault();
            alert('Дата записи не может быть в прошлом');
            return false;
        }
    });

    // Установка минимальной даты (сегодня)
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('booking_date').min = today;
});
</script>
