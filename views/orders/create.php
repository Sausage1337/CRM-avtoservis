<div class="container-fluid">
    <!-- Заголовок страницы -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-plus text-primary"></i> Создание нового заказа
                    </h1>
                    <p class="text-muted mb-0">Заполните форму для создания нового заказа</p>
                </div>
                <a href="index.php?page=orders" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Назад к списку
                </a>
            </div>
        </div>
    </div>

    <!-- Форма создания заказа -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list"></i> Информация о заказе
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=orders&action=create" id="createOrderForm">
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
                                                <option value="<?= $client['id'] ?>">
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
                                            <option value="">Сначала выберите клиента</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="priority" class="form-label">
                                            <i class="fas fa-exclamation-triangle text-primary"></i> Приоритет
                                        </label>
                                        <select class="form-select" id="priority" name="priority">
                                            <option value="low">Низкий</option>
                                            <option value="normal" selected>Обычный</option>
                                            <option value="high">Высокий</option>
                                            <option value="urgent">Срочный</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="estimated_cost" class="form-label">
                                            <i class="fas fa-ruble-sign text-primary"></i> Предварительная стоимость
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="estimated_cost" name="estimated_cost" 
                                                   min="0" step="100" placeholder="0">
                                            <span class="input-group-text">₽</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left text-primary"></i> Описание работ
                                    </label>
                                    <textarea class="form-control" id="description" name="description" rows="4" 
                                              placeholder="Опишите проблему или необходимые работы..."></textarea>
                                </div>
                            </div>

                            <!-- Выбор услуг и скидки -->
                            <div class="col-md-4">
                                <div class="services-panel">
                                    <div class="services-header">
                                        <div class="services-title">
                                            <i class="fas fa-concierge-bell"></i>
                                            <span>Выбор услуг</span>
                                        </div>
                                        <div class="services-counter">
                                            <span id="selectedServicesCount">0</span>
                                        </div>
                                    </div>
                                    
                                    <div class="services-search">
                                        <div class="search-input-wrapper">
                                            <i class="fas fa-search"></i>
                                            <input type="text" id="service_search" placeholder="Поиск услуги...">
                                        </div>
                                    </div>
                                        
                                    <div class="services-list">
                                        <?php 
                                        $currentCategory = '';
                                        foreach ($services as $service): 
                                            $serviceCategory = $service['category'] ?? '';
                                            if ($serviceCategory !== $currentCategory): 
                                                if ($currentCategory !== ''): ?>
                                                    </div>
                                                <?php endif; 
                                                $currentCategory = $serviceCategory; ?>
                                                <div class="service-category">
                                                    <div class="category-header">
                                                        <?= $currentCategory ?: 'Без категории' ?>
                                                    </div>
                                                    <div class="category-services">
                                            <?php endif; ?>
                                            
                                            <div class="service-item" data-service-name="<?= htmlspecialchars(strtolower($service['name'])) ?>">
                                                <input type="checkbox" 
                                                       name="services[]" 
                                                       value="<?= $service['id'] ?>" 
                                                       id="service_<?= $service['id'] ?>"
                                                       class="service-checkbox">
                                                <label for="service_<?= $service['id'] ?>" class="service-card">
                                                    <div class="service-content">
                                                        <div class="service-info">
                                                            <div class="service-name">
                                                                <?= htmlspecialchars($service['name']) ?>
                                                            </div>
                                                            <?php if (!empty($service['description'])): ?>
                                                                <div class="service-description">
                                                                    <?= htmlspecialchars($service['description']) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="service-details">
                                                            <div class="service-price">
                                                                <?= number_format($service['price'], 0, ',', ' ') ?> ₽
                                                            </div>
                                                            <?php if (!empty($service['duration'])): ?>
                                                                <div class="service-duration">
                                                                    <i class="fas fa-clock"></i>
                                                                    <?= $service['duration'] ?> мин
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="service-checkbox-custom">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                        
                                        <?php if ($currentCategory !== ''): ?>
                                            </div></div>
                                        <?php endif; ?>
                                    </div>
                                        
                                        <hr>
                                        <div class="mb-3">
                                            <label class="form-label"><i class="fas fa-ticket"></i> Промокод</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="promo_code" placeholder="Введите промокод">
                                                <button type="button" class="btn btn-outline-secondary" id="apply_promo">Применить</button>
                                            </div>
                                            <div class="form-text" id="promo_feedback"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label"><i class="fas fa-percent"></i> Скидка, %</label>
                                            <input type="number" class="form-control" id="discount_percent" name="discount_percent" min="0" max="100" value="0">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label"><i class="fas fa-wrench"></i> Своя услуга</label>
                                            <div class="input-group mb-2">
                                                <input type="text" id="custom_service_name" class="form-control" placeholder="Название услуги">
                                                <input type="number" id="custom_service_price" class="form-control" placeholder="Цена" min="0" step="1">
                                                <button type="button" id="add_custom_service" class="btn btn-outline-primary"><i class="fas fa-plus"></i></button>
                                            </div>
                                            <div id="custom_services_list" class="small"></div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Итого услуг:</strong>
                                            <span id="selectedServicesCount" class="badge bg-info">0</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <strong>К оплате:</strong>
                                            <span id="amountToPay" class="badge bg-success">0 ₽</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Промокод, скидки и итоговая стоимость -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="pricing-panel">
                                    <div class="pricing-header">
                                        <div class="pricing-title">
                                            <i class="fas fa-calculator"></i>
                                            <span>Расчет стоимости</span>
                                        </div>
                                    </div>
                                    
                                    <div class="pricing-content">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="promo-section">
                                                    <div class="section-header">
                                                        <i class="fas fa-tag"></i>
                                                        <span>Промокод</span>
                                                    </div>
                                                    <div class="promo-input">
                                                        <input type="text" id="promo_code" placeholder="Введите промокод">
                                                        <button type="button" id="apply_promo">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                    <div class="promo-feedback" id="promo_feedback"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="discount-section">
                                                    <div class="section-header">
                                                        <i class="fas fa-percent"></i>
                                                        <span>Скидка</span>
                                                    </div>
                                                    <div class="discount-input">
                                                        <input type="number" id="discount_percent" name="discount_percent" 
                                                               min="0" max="100" value="0" placeholder="0">
                                                        <span class="discount-unit">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="custom-service-section">
                                                    <div class="section-header">
                                                        <i class="fas fa-plus-circle"></i>
                                                        <span>Добавить услугу</span>
                                                    </div>
                                                    <div class="custom-inputs">
                                                        <input type="text" id="custom_service_name" placeholder="Название">
                                                        <input type="number" id="custom_service_price" placeholder="Цена" min="0" step="1">
                                                        <button type="button" id="add_custom_service">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="custom_services_list" class="custom-services-list mt-3"></div>
                                        
                                        <!-- Итоговая стоимость -->
                                        <div class="pricing-summary">
                                            <div class="summary-row">
                                                <span class="summary-label">Услуг выбрано:</span>
                                                <span class="summary-value" id="selectedServicesCount">0</span>
                                            </div>
                                            <div class="summary-row">
                                                <span class="summary-label">Стоимость услуг:</span>
                                                <span class="summary-value" id="servicesTotal">0 ₽</span>
                                            </div>
                                            <div class="summary-row discount-row" id="discountRow" style="display: none;">
                                                <span class="summary-label">Скидка:</span>
                                                <span class="summary-value" id="discountAmount">-0 ₽</span>
                                            </div>
                                            <div class="summary-row total-row">
                                                <span class="summary-label">К оплате:</span>
                                                <span class="summary-value" id="totalAmount">0 ₽</span>
                                            </div>
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
                                        <i class="fas fa-save"></i> Создать заказ
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
// Загрузка автомобилей клиента
document.getElementById('client_id').addEventListener('change', function() {
    const clientId = this.value;
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
});

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
        recalcAmount();
    }
});

// Хранение цен услуг
const servicePrices = {};

// Инициализация цен услуг при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Сохраняем цены всех услуг
    document.querySelectorAll('.service-item').forEach(item => {
        const checkbox = item.querySelector('.service-checkbox');
        const priceElement = item.querySelector('.service-price');
        if (checkbox && priceElement) {
            const serviceId = checkbox.value;
            const priceText = priceElement.textContent.replace(/[^\d]/g, '');
            servicePrices[serviceId] = parseFloat(priceText) || 0;
        }
    });
    
    updateCalculation();
});

function updateSelectedServicesCount() {
    const selectedServices = document.querySelectorAll('input[name="services[]"]:checked');
    const count = selectedServices.length;
    
    // Обновляем все счетчики
    document.querySelectorAll('#selectedServicesCount').forEach(el => {
        el.textContent = count;
    });
    
    // Обновляем счетчик в заголовке панели услуг
    const headerCounter = document.querySelector('.services-counter span');
    if (headerCounter) {
        headerCounter.textContent = count;
    }
}

function updateCalculation() {
    const selectedServices = document.querySelectorAll('input[name="services[]"]:checked');
    let servicesTotal = 0;
    
    // Считаем стоимость выбранных услуг
    selectedServices.forEach(input => {
        const serviceId = input.value;
        if (servicePrices[serviceId]) {
            servicesTotal += servicePrices[serviceId];
        }
    });
    
    // Добавляем кастомные услуги
    document.querySelectorAll('#custom_services_list .cs-price').forEach(el => {
        const val = parseFloat(el.getAttribute('data-value') || '0');
        servicesTotal += isNaN(val) ? 0 : val;
    });
    
    // Получаем скидку
    const discountPercent = parseFloat(document.getElementById('discount_percent').value || '0');
    const discountAmount = Math.round(servicesTotal * discountPercent / 100);
    const finalTotal = servicesTotal - discountAmount;
    
    // Обновляем отображение
    updateSelectedServicesCount();
    
    const servicesTotalEl = document.getElementById('servicesTotal');
    if (servicesTotalEl) {
        servicesTotalEl.textContent = formatPrice(servicesTotal);
    }
    
    const discountAmountEl = document.getElementById('discountAmount');
    const discountRow = document.getElementById('discountRow');
    if (discountAmountEl && discountRow) {
        if (discountPercent > 0) {
            discountAmountEl.textContent = '-' + formatPrice(discountAmount);
            discountRow.style.display = 'flex';
        } else {
            discountRow.style.display = 'none';
        }
    }
    
    const totalAmountEl = document.getElementById('totalAmount');
    if (totalAmountEl) {
        totalAmountEl.textContent = formatPrice(finalTotal);
    }
    
    // Обновляем старый элемент для совместимости
    const amountEl = document.getElementById('amountToPay');
    if (amountEl) {
        amountEl.textContent = formatPrice(finalTotal);
    }
}

function formatPrice(amount) {
    return new Intl.NumberFormat('ru-RU').format(amount) + ' ₽';
}

// Алиас для обратной совместимости
function recalcAmount() {
    updateCalculation();
}

// Обработчики событий для новых элементов
document.getElementById('discount_percent').addEventListener('input', () => updateCalculation());

const estimatedCostEl = document.getElementById('estimated_cost');
if (estimatedCostEl) {
    estimatedCostEl.addEventListener('input', () => updateCalculation());
}

// Добавляем обработчики для чекбоксов услуг
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('service-checkbox')) {
        updateCalculation();
    }
});

document.getElementById('add_custom_service').addEventListener('click', () => {
    const name = (document.getElementById('custom_service_name').value || '').trim();
    const price = parseFloat(document.getElementById('custom_service_price').value || '0');
    if (!name || isNaN(price) || price <= 0) {
        alert('Введите название услуги и корректную цену');
        return;
    }
    
    const wrap = document.getElementById('custom_services_list');
    const id = 'cs_' + Date.now();
    const row = document.createElement('div');
    row.className = 'custom-service-item';
    row.innerHTML = `
        <div class="custom-service-content">
            <div class="custom-service-name">${name}</div>
            <div class="custom-service-price cs-price" data-value="${price}">${formatPrice(price)}</div>
        </div>
        <button type="button" class="custom-service-remove" onclick="this.parentElement.remove(); updateCalculation();">
            <i class="fas fa-times"></i>
        </button>
        <input type="hidden" name="custom_services[][name]" value="${name}">
        <input type="hidden" name="custom_services[][price]" value="${price}">
    `;
    wrap.appendChild(row);
    
    document.getElementById('custom_service_name').value = '';
    document.getElementById('custom_service_price').value = '';
    updateCalculation();
});

document.getElementById('apply_promo').addEventListener('click', function() {
    const code = (document.getElementById('promo_code').value || '').trim();
    const feedback = document.getElementById('promo_feedback');
    if (!code) { feedback.textContent = 'Введите промокод'; feedback.className = 'form-text text-warning'; return; }
    fetch('index.php?page=orders&action=validatePromo&code=' + encodeURIComponent(code))
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('discount_percent').value = data.discount_percent || 0;
                feedback.textContent = 'Промокод применен: ' + (data.description || '');
                feedback.className = 'form-text text-success';
                recalcAmount(true);
            } else {
                feedback.textContent = data.message || 'Промокод не найден или не активен';
                feedback.className = 'form-text text-danger';
            }
        })
        .catch(() => { feedback.textContent = 'Ошибка проверки промокода'; feedback.className = 'form-text text-danger'; });
});

// Валидация формы
document.getElementById('createOrderForm').addEventListener('submit', function(e) {
    const clientId = document.getElementById('client_id').value;
    const vehicleId = document.getElementById('vehicle_id').value;
    
    if (!clientId || !vehicleId) {
        e.preventDefault();
        alert('Пожалуйста, выберите клиента и автомобиль');
        return;
    }
    
    // Показываем индикатор загрузки
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Создание...';
    submitBtn.disabled = true;
});

// Инициализация
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedServicesCount();
    recalcAmount();
    
    // Apple-style service card interactions
    document.querySelectorAll('.service-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        
        card.addEventListener('click', function(e) {
            if (e.target === checkbox) return;
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        });
        
        card.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(0, 122, 255, 0.05)';
            this.style.borderColor = 'var(--apple-blue)';
        });
        
        card.addEventListener('mouseleave', function() {
            if (!checkbox.checked) {
                this.style.background = 'var(--apple-gray)';
                this.style.borderColor = 'transparent';
            }
        });
        
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                card.style.background = 'rgba(0, 122, 255, 0.1)';
                card.style.borderColor = 'var(--apple-blue)';
            } else {
                card.style.background = 'var(--apple-gray)';
                card.style.borderColor = 'transparent';
            }
        });
    });
});
</script>

<style>
.service-card {
    transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.service-card:hover {
    transform: scale(1.02);
}

.form-check-input:checked {
    background-color: var(--apple-blue);
    border-color: var(--apple-blue);
}

.form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
}

.category-title {
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
    padding: 8px 0;
}

.services-list::-webkit-scrollbar {
    width: 6px;
}

.services-list::-webkit-scrollbar-track {
    background: var(--apple-gray);
    border-radius: 3px;
}

.services-list::-webkit-scrollbar-thumb {
    background: var(--apple-gray-3);
    border-radius: 3px;
}

.services-list::-webkit-scrollbar-thumb:hover {
    background: var(--apple-gray-5);
    }

    /* Services Panel Styles */
    .services-panel {
        background: white;
        border: 1px solid var(--apple-gray-2);
        border-radius: var(--apple-border-radius-lg);
        overflow: hidden;
    }

    .services-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        background: var(--apple-gray);
        border-bottom: 1px solid var(--apple-gray-2);
    }

    .services-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: var(--apple-black);
        font-size: 16px;
    }

    .services-title i {
        color: var(--apple-blue);
        font-size: 18px;
    }

    .services-counter {
        background: var(--apple-blue);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
    }

    .services-search {
        padding: 16px 24px;
        border-bottom: 1px solid var(--apple-gray-2);
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 12px;
        color: var(--apple-gray-5);
        font-size: 14px;
        z-index: 1;
    }

    .search-input-wrapper input {
        width: 100%;
        padding: 12px 12px 12px 36px;
        border: 1px solid var(--apple-gray-3);
        border-radius: var(--apple-border-radius);
        font-size: 14px;
        background: white;
        transition: all 0.2s ease;
    }

    .search-input-wrapper input:focus {
        outline: none;
        border-color: var(--apple-blue);
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
    }

    .services-list {
        max-height: 350px;
        overflow-y: auto;
        padding: 16px 0;
    }

    .service-category {
        margin-bottom: 20px;
    }

    .category-header {
        padding: 8px 24px;
        font-size: 12px;
        font-weight: 600;
        color: var(--apple-gray-5);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: var(--apple-gray);
        margin: 0 16px;
        border-radius: var(--apple-border-radius);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .category-services {
        padding: 8px 0;
    }

    .service-item {
        margin: 0 16px 8px;
        position: relative;
    }

    .service-checkbox {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .service-card {
        display: block;
        background: var(--apple-gray);
        border: 1px solid transparent;
        border-radius: var(--apple-border-radius-lg);
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .service-card:hover {
        border-color: var(--apple-blue);
        box-shadow: 0 2px 12px rgba(0, 122, 255, 0.15);
        transform: translateY(-1px);
    }

    .service-checkbox:checked + .service-card {
        background: rgba(0, 122, 255, 0.05);
        border-color: var(--apple-blue);
        box-shadow: 0 2px 16px rgba(0, 122, 255, 0.2);
    }

    .service-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .service-info {
        flex: 1;
    }

    .service-name {
        font-weight: 600;
        color: var(--apple-black);
        font-size: 14px;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .service-description {
        font-size: 12px;
        color: var(--apple-gray-6);
        line-height: 1.4;
    }

    .service-details {
        text-align: right;
        flex-shrink: 0;
    }

    .service-price {
        font-weight: 700;
        color: var(--apple-blue);
        font-size: 14px;
        margin-bottom: 2px;
    }

    .service-duration {
        font-size: 11px;
        color: var(--apple-gray-5);
        display: flex;
        align-items: center;
        gap: 4px;
        justify-content: flex-end;
    }

    .service-duration i {
        font-size: 10px;
    }

    .service-checkbox-custom {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 20px;
        height: 20px;
        border: 2px solid var(--apple-gray-3);
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .service-checkbox-custom i {
        font-size: 10px;
        color: white;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .service-checkbox:checked + .service-card .service-checkbox-custom {
        background: var(--apple-blue);
        border-color: var(--apple-blue);
    }

    .service-checkbox:checked + .service-card .service-checkbox-custom i {
        opacity: 1;
    }

    .services-footer {
        padding: 20px 24px;
        background: var(--apple-gray);
        border-top: 1px solid var(--apple-gray-2);
    }

    .promo-section,
    .discount-section,
    .custom-service-section {
        margin-bottom: 20px;
    }

    .promo-header,
    .discount-header,
    .custom-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        color: var(--apple-black);
        font-size: 14px;
        margin-bottom: 8px;
    }

    .promo-header i,
    .discount-header i,
    .custom-header i {
        color: var(--apple-blue);
        font-size: 14px;
    }

    .promo-input,
    .custom-inputs {
        display: flex;
        gap: 8px;
    }

    .promo-input input,
    .custom-inputs input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid var(--apple-gray-3);
        border-radius: var(--apple-border-radius);
        font-size: 13px;
        background: white;
    }

    .promo-input input:focus,
    .custom-inputs input:focus {
        outline: none;
        border-color: var(--apple-blue);
        box-shadow: 0 0 0 2px rgba(0, 122, 255, 0.1);
    }

    .promo-input button,
    .custom-inputs button {
        padding: 8px 12px;
        background: var(--apple-blue);
        color: white;
        border: none;
        border-radius: var(--apple-border-radius);
        cursor: pointer;
        transition: background 0.2s ease;
        font-size: 12px;
    }

    .promo-input button:hover,
    .custom-inputs button:hover {
        background: var(--apple-blue-dark);
    }

    .discount-input {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .discount-input input {
        width: 80px;
        padding: 8px 12px;
        border: 1px solid var(--apple-gray-3);
        border-radius: var(--apple-border-radius);
        font-size: 13px;
        background: white;
        text-align: center;
    }

    .discount-input input:focus {
        outline: none;
        border-color: var(--apple-blue);
        box-shadow: 0 0 0 2px rgba(0, 122, 255, 0.1);
    }

    .discount-unit {
        font-weight: 500;
        color: var(--apple-gray-6);
        font-size: 13px;
    }

    .promo-feedback {
        margin-top: 4px;
        font-size: 11px;
        color: var(--apple-gray-5);
    }

    .custom-services-list {
        margin-top: 8px;
        font-size: 12px;
        color: var(--apple-gray-6);
    }

    .services-summary {
        border-top: 1px solid var(--apple-gray-3);
        padding-top: 16px;
        margin-top: 16px;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-label {
        font-weight: 600;
        color: var(--apple-black);
        font-size: 14px;
    }

    .summary-count {
        background: var(--apple-blue);
        color: white;
        padding: 4px 8px;
        border-radius: var(--apple-border-radius);
        font-size: 12px;
        font-weight: 600;
        min-width: 24px;
        text-align: center;
    }

    /* Custom scrollbar for services list */
    .services-list::-webkit-scrollbar {
        width: 4px;
    }

    .services-list::-webkit-scrollbar-track {
        background: var(--apple-gray);
    }

    .services-list::-webkit-scrollbar-thumb {
        background: var(--apple-gray-4);
        border-radius: 2px;
    }

    .services-list::-webkit-scrollbar-thumb:hover {
        background: var(--apple-gray-5);
    }

    /* Pricing Panel Styles */
    .pricing-panel {
        background: white;
        border: 1px solid var(--apple-gray-2);
        border-radius: var(--apple-border-radius-lg);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .pricing-header {
        background: var(--apple-gray);
        padding: 20px 24px;
        border-bottom: 1px solid var(--apple-gray-2);
    }

    .pricing-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: var(--apple-black);
        font-size: 16px;
    }

    .pricing-title i {
        color: var(--apple-blue);
        font-size: 18px;
    }

    .pricing-content {
        padding: 24px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        color: var(--apple-black);
        font-size: 14px;
        margin-bottom: 12px;
    }

    .section-header i {
        color: var(--apple-blue);
        font-size: 14px;
    }

    .promo-input,
    .custom-inputs {
        display: flex;
        gap: 8px;
    }

    .promo-input input,
    .custom-inputs input {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid var(--apple-gray-3);
        border-radius: var(--apple-border-radius);
        font-size: 14px;
        background: white;
        transition: all 0.2s ease;
    }

    .promo-input input:focus,
    .custom-inputs input:focus {
        outline: none;
        border-color: var(--apple-blue);
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
    }

    .promo-input button,
    .custom-inputs button {
        padding: 10px 14px;
        background: var(--apple-blue);
        color: white;
        border: none;
        border-radius: var(--apple-border-radius);
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
        min-width: 44px;
    }

    .promo-input button:hover,
    .custom-inputs button:hover {
        background: var(--apple-blue-dark);
        transform: translateY(-1px);
    }

    .discount-input {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .discount-input input {
        width: 100px;
        padding: 10px 12px;
        border: 1px solid var(--apple-gray-3);
        border-radius: var(--apple-border-radius);
        font-size: 14px;
        background: white;
        text-align: center;
        transition: all 0.2s ease;
    }

    .discount-input input:focus {
        outline: none;
        border-color: var(--apple-blue);
        box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
    }

    .discount-unit {
        font-weight: 500;
        color: var(--apple-gray-6);
        font-size: 14px;
    }

    .promo-feedback {
        margin-top: 6px;
        font-size: 12px;
        color: var(--apple-gray-5);
    }

    .custom-services-list {
        font-size: 13px;
        color: var(--apple-gray-6);
    }

    .pricing-summary {
        background: var(--apple-gray);
        margin: 24px -24px -24px;
        padding: 20px 24px;
        border-top: 1px solid var(--apple-gray-2);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-label {
        font-weight: 500;
        color: var(--apple-black);
        font-size: 14px;
    }

    .summary-value {
        font-weight: 600;
        color: var(--apple-blue);
        font-size: 14px;
    }

    .total-row {
        border-top: 1px solid var(--apple-gray-3);
        padding-top: 12px;
        margin-top: 8px;
    }

    .total-row .summary-label {
        font-size: 16px;
        font-weight: 600;
    }

    .total-row .summary-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--apple-black);
    }

    .discount-row {
        color: #FF3B30;
    }

    .discount-row .summary-value {
        color: #FF3B30;
    }

    /* Custom Service Item Styles */
    .custom-service-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--apple-gray);
        border: 1px solid var(--apple-gray-3);
        border-radius: var(--apple-border-radius);
        padding: 8px 12px;
        margin-bottom: 6px;
    }

    .custom-service-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex: 1;
        gap: 12px;
    }

    .custom-service-name {
        font-weight: 500;
        color: var(--apple-black);
        font-size: 13px;
    }

    .custom-service-price {
        font-weight: 600;
        color: var(--apple-blue);
        font-size: 13px;
    }

    .custom-service-remove {
        background: none;
        border: none;
        color: #FF3B30;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: background 0.2s ease;
        font-size: 12px;
    }

    .custom-service-remove:hover {
        background: rgba(255, 59, 48, 0.1);
    }
</style>
