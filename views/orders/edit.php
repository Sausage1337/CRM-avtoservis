<div class="container-fluid">
    <!-- Заголовок страницы -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit text-primary"></i> Редактирование заказа
                    </h1>
                    <p class="text-muted mb-0">Заказ №<?= htmlspecialchars($order['order_number'] ?? '#' . $order['id']) ?></p>
                </div>
                <div>
                    <a href="index.php?page=orders&action=view&id=<?= $order['id'] ?>" class="btn btn-info me-2">
                        <i class="fas fa-eye"></i> Просмотр
                    </a>
                    <a href="index.php?page=orders" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад к списку
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Форма редактирования заказа -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list"></i> Информация о заказе
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=orders&action=edit&id=<?= $order['id'] ?>" id="editOrderForm">
                        <div class="row">
                            <!-- Основная информация -->
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="client_id" class="form-label">
                                            <i class="fas fa-user text-primary"></i> Клиент <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="client_id" name="client_id" required>
                                            <option value="">Выберите клиента</option>
                                            <?php foreach ($clients as $client): ?>
                                                <option value="<?= $client['id'] ?>" <?= $client['id'] == $order['client_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($client['full_name']) ?> (<?= htmlspecialchars($client['phone']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="vehicle_id" class="form-label">
                                            <i class="fas fa-car text-primary"></i> Автомобиль <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                            <option value="">Загрузка...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="priority" class="form-label">
                                            <i class="fas fa-exclamation-triangle text-primary"></i> Приоритет
                                        </label>
                                        <select class="form-select" id="priority" name="priority">
                                            <?php $currentPriority = $order['priority'] ?? 'normal'; ?>
                                            <option value="low" <?= $currentPriority === 'low' ? 'selected' : '' ?>>Низкий</option>
                                            <option value="normal" <?= $currentPriority === 'normal' ? 'selected' : '' ?>>Обычный</option>
                                            <option value="high" <?= $currentPriority === 'high' ? 'selected' : '' ?>>Высокий</option>
                                            <option value="urgent" <?= $currentPriority === 'urgent' ? 'selected' : '' ?>>Срочный</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">
                                            <i class="fas fa-info-circle text-primary"></i> Статус
                                        </label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="new" <?= $order['status'] === 'new' ? 'selected' : '' ?>>Новый</option>
                                            <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Подтвержден</option>
                                            <option value="in_progress" <?= $order['status'] === 'in_progress' ? 'selected' : '' ?>>В работе</option>
                                            <option value="waiting_parts" <?= $order['status'] === 'waiting_parts' ? 'selected' : '' ?>>Ожидает запчасти</option>
                                            <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Завершен</option>
                                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Отменен</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="estimated_cost" class="form-label">
                                            <i class="fas fa-ruble-sign text-primary"></i> Предварительная стоимость
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="estimated_cost" name="estimated_cost" 
                                                   min="0" step="100" placeholder="0" 
                                                   value="<?= htmlspecialchars($order['estimated_cost']) ?>">
                                            <span class="input-group-text">₽</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-calendar text-primary"></i> Дата создания
                                        </label>
                                        <input type="text" class="form-control" value="<?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>" readonly>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left text-primary"></i> Описание работ
                                    </label>
                                    <textarea class="form-control" id="description" name="description" rows="4" 
                                              placeholder="Опишите проблему или необходимые работы..."><?= htmlspecialchars($order['notes'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Выбор услуг -->
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-tools"></i> Выбор услуг
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="service_search" class="form-label">Поиск услуги</label>
                                            <input type="text" class="form-control" id="service_search" 
                                                   placeholder="Введите название услуги...">
                                        </div>
                                        
                                        <div class="services-list" style="max-height: 300px; overflow-y: auto;">
                                            <?php 
                                            $selectedServices = array_column($orderItems, 'service_id');
                                            foreach ($services as $service): 
                                            ?>
                                                <div class="form-check service-item">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="services[]" value="<?= $service['id'] ?>" 
                                                           id="service_<?= $service['id'] ?>"
                                                           <?= in_array($service['id'], $selectedServices) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="service_<?= $service['id'] ?>">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span><?= htmlspecialchars($service['name']) ?></span>
                                                            <span class="badge bg-primary"><?= number_format($service['price'], 0, ',', ' ') ?> ₽</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Итого услуг:</strong>
                                            <span id="selectedServicesCount" class="badge bg-info">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Кнопки действий -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="index.php?page=orders" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Отмена
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Сохранить изменения
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Загрузка автомобилей клиента при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    const clientId = document.getElementById('client_id').value;
    if (clientId) {
        loadClientVehicles(clientId, <?= $order['vehicle_id'] ?>);
    }
    updateSelectedServicesCount();
});

// Загрузка автомобилей клиента
document.getElementById('client_id').addEventListener('change', function() {
    const clientId = this.value;
    loadClientVehicles(clientId);
});

function loadClientVehicles(clientId, selectedVehicleId = null) {
    const vehicleSelect = document.getElementById('vehicle_id');
    
    if (!clientId) {
        vehicleSelect.innerHTML = '<option value="">Сначала выберите клиента</option>';
        return;
    }
    
    // Очищаем текущий список
    vehicleSelect.innerHTML = '<option value="">Загрузка...</option>';
    
    // Загружаем автомобили клиента
    fetch(`index.php?page=process&action=get_client_vehicles&client_id=${clientId}`)
        .then(response => response.json())
        .then(data => {
            vehicleSelect.innerHTML = '<option value="">Выберите автомобиль</option>';
            
            if (data.success && data.vehicles.length > 0) {
                data.vehicles.forEach(vehicle => {
                    const option = document.createElement('option');
                    option.value = vehicle.id;
                    option.textContent = `${vehicle.brand} ${vehicle.model} (${vehicle.license_plate})`;
                    if (selectedVehicleId && vehicle.id == selectedVehicleId) {
                        option.selected = true;
                    }
                    vehicleSelect.appendChild(option);
                });
            } else {
                vehicleSelect.innerHTML = '<option value="">У клиента нет автомобилей</option>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            vehicleSelect.innerHTML = '<option value="">Ошибка загрузки</option>';
        });
}

// Поиск по услугам
document.getElementById('service_search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const serviceItems = document.querySelectorAll('.service-item');
    
    serviceItems.forEach(item => {
        const serviceName = item.querySelector('label span').textContent.toLowerCase();
        if (serviceName.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Подсчет выбранных услуг
document.addEventListener('change', function(e) {
    if (e.target.name === 'services[]') {
        updateSelectedServicesCount();
    }
});

function updateSelectedServicesCount() {
    const selectedServices = document.querySelectorAll('input[name="services[]"]:checked');
    document.getElementById('selectedServicesCount').textContent = selectedServices.length;
}

// Валидация формы
document.getElementById('editOrderForm').addEventListener('submit', function(e) {
    const clientId = document.getElementById('client_id').value;
    const vehicleId = document.getElementById('vehicle_id').value;
    
    if (!clientId || !vehicleId) {
        e.preventDefault();
        alert('Пожалуйста, выберите клиента и автомобиль');
        return;
    }
    
    // Показываем индикатор загрузки
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
    submitBtn.disabled = true;
});
</script>
