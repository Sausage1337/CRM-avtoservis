<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-calculator text-info me-2"></i>
            <span class="text-info">Калькуляция</span> услуг
        </h2>
        <p class="text-muted mb-0">Расчет стоимости работ и услуг для клиентов</p>
    </div>
    <a href="index.php?page=process" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Назад к процессам
    </a>
</div>

<!-- Форма калькуляции -->
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Создать новую калькуляцию
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=process&action=calculation" id="calculationForm">
                    <!-- Описание калькуляции -->
                    <div class="mb-4">
                        <label for="description" class="form-label">
                            <i class="fas fa-edit text-info me-2"></i>Описание калькуляции
                        </label>
                        <textarea class="form-control form-control-lg" id="description" name="description" rows="3" 
                                  placeholder="Опишите, для какого автомобиля и какие работы планируются..." required></textarea>
                    </div>

                    <!-- Контейнер для услуг -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-tools text-warning me-2"></i>Услуги и работы
                        </label>
                        <div id="servicesContainer">
                            <div class="service-row row mb-3 p-3 bg-light rounded border">
                                <div class="col-md-4">
                                    <label class="form-label small">Услуга</label>
                                    <select class="form-select service-select" name="services[]" required>
                                        <option value="">Выберите услугу</option>
                                        <?php foreach ($services as $service): ?>
                                        <option value="<?= $service['id'] ?>" data-price="<?= $service['price'] ?>">
                                            <?= htmlspecialchars($service['name']) ?> - <?= number_format($service['price'], 0, ',', ' ') ?> ₽
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Кол-во</label>
                                    <input type="number" class="form-control quantity-input" name="quantities[]" 
                                           value="1" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Цена</label>
                                    <input type="number" class="form-control price-input" name="prices[]" 
                                           step="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Сумма</label>
                                    <div class="form-control-plaintext amount-display fw-bold text-primary">
                                        0.00 ₽
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-outline-info btn-sm" id="addService">
                            <i class="fas fa-plus me-2"></i>Добавить услугу
                        </button>
                    </div>

                    <!-- Итоговая информация -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="calculation-summary bg-light p-3 rounded">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-calculator me-2"></i>Итоги калькуляции
                                </h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Общая сумма:</span>
                                    <span class="h4 text-primary mb-0" id="totalAmount">0.00 ₽</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted">Количество услуг:</span>
                                    <span class="badge bg-info rounded-pill" id="servicesCount">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="calculation-tips bg-info bg-opacity-10 p-3 rounded border border-info">
                                <h6 class="text-info mb-2">
                                    <i class="fas fa-lightbulb me-2"></i>Советы
                                </h6>
                                <ul class="small text-muted mb-0">
                                    <li>Укажите точное количество для каждой услуги</li>
                                    <li>Проверьте цены перед сохранением</li>
                                    <li>Добавьте описание для ясности</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-info btn-lg">
                            <i class="fas fa-save me-2"></i>Сохранить калькуляцию
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Последние калькуляции -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Последние калькуляции
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($calculations)): ?>
                    <?php foreach ($calculations as $calc): ?>
                    <div class="calculation-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    <i class="fas fa-calculator text-info me-1"></i>
                                    Калькуляция #<?= $calc['id'] ?>
                                </h6>
                                <p class="text-muted small mb-1">
                                    <?= htmlspecialchars($calc['description'] ?: 'Без описания') ?>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    <?= htmlspecialchars($calc['created_by_name'] ?? 'Система') ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= $calc['status'] === 'ready' ? 'success' : 
                                                       ($calc['status'] === 'draft' ? 'warning' : 'secondary') ?> rounded-pill">
                                    <?= $calc['status'] === 'ready' ? 'Готово' : 
                                       ($calc['status'] === 'draft' ? 'Черновик' : 'Использовано') ?>
                                </span>
                                <div class="mt-2">
                                    <strong class="text-primary h6 mb-0">
                                        <?= number_format($calc['total_amount'], 0, ',', ' ') ?> ₽
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <div class="empty-state">
                            <i class="fas fa-calculator fa-3x mb-3"></i>
                            <h6>Калькуляции пока не созданы</h6>
                            <p class="small">Создайте первую калькуляцию для расчета стоимости услуг</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Информация о калькуляции -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle text-info me-2"></i>О калькуляции
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    <strong>Калькуляция</strong> - это предварительный расчет стоимости работ.
                </p>
                <ul class="small text-muted mb-0">
                    <li>Можно создать независимо от других этапов</li>
                    <li>Автоматический подсчет общей суммы</li>
                    <li>Возможность редактирования до использования</li>
                    <li>Связывание с заказ-нарядом</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для калькуляции */
.calculation-summary {
    border-left: 4px solid #17a2b8;
}

.calculation-tips {
    border-left: 4px solid #17a2b8;
}

.service-row {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.service-row:hover {
    border-color: #17a2b8;
    box-shadow: 0 2px 8px rgba(23, 162, 184, 0.1);
}

.calculation-item {
    transition: all 0.3s ease;
}

.calculation-item:hover {
    background-color: rgba(0,0,0,0.02);
    padding-left: 10px;
}

.empty-state {
    padding: 20px;
}

.empty-state i {
    opacity: 0.5;
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
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.form-select:focus {
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const servicesContainer = document.getElementById('servicesContainer');
    const addServiceBtn = document.getElementById('addService');
    const totalAmountDisplay = document.getElementById('totalAmount');
    const servicesCountDisplay = document.getElementById('servicesCount');

    // Функция для обновления суммы строки
    function updateRowAmount(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const amount = quantity * price;
        
        row.querySelector('.amount-display').textContent = amount.toFixed(2) + ' ₽';
        updateTotal();
    }

    // Функция для обновления общей суммы
    function updateTotal() {
        let total = 0;
        let count = 0;
        
        document.querySelectorAll('.service-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            total += quantity * price;
            if (quantity > 0 && price > 0) count++;
        });
        
        totalAmountDisplay.textContent = total.toFixed(2) + ' ₽';
        servicesCountDisplay.textContent = count;
    }

    // Обработчик изменения услуги
    servicesContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('service-select')) {
            const row = e.target.closest('.service-row');
            const priceInput = row.querySelector('.price-input');
            const selectedOption = e.target.options[e.target.selectedIndex];
            
            if (selectedOption.dataset.price) {
                priceInput.value = selectedOption.dataset.price;
                updateRowAmount(row);
            }
        }
    });

    // Обработчики изменения количества и цены
    servicesContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
            const row = e.target.closest('.service-row');
            updateRowAmount(row);
        }
    });

    // Добавление новой строки услуги
    addServiceBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'service-row row mb-3 p-3 bg-light rounded border';
        newRow.innerHTML = `
            <div class="col-md-4">
                <label class="form-label small">Услуга</label>
                <select class="form-select service-select" name="services[]" required>
                    <option value="">Выберите услугу</option>
                    <?php foreach ($services as $service): ?>
                    <option value="<?= $service['id'] ?>" data-price="<?= $service['price'] ?>">
                        <?= htmlspecialchars($service['name']) ?> - <?= number_format($service['price'], 0, ',', ' ') ?> ₽
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Кол-во</label>
                <input type="number" class="form-control quantity-input" name="quantities[]" value="1" min="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Цена</label>
                <input type="number" class="form-control price-input" name="prices[]" step="0.01" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Сумма</label>
                <div class="form-control-plaintext amount-display fw-bold text-primary">0.00 ₽</div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        servicesContainer.appendChild(newRow);
        updateTotal();
    });

    // Удаление строки услуги
    servicesContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-service') || e.target.closest('.remove-service')) {
            const row = e.target.closest('.service-row');
            if (document.querySelectorAll('.service-row').length > 1) {
                row.remove();
                updateTotal();
            }
        }
    });

    // Валидация формы
    document.getElementById('calculationForm').addEventListener('submit', function(e) {
        const description = document.getElementById('description').value.trim();
        const services = document.querySelectorAll('.service-select');
        let hasValidServices = false;
        
        services.forEach(select => {
            if (select.value) hasValidServices = true;
        });
        
        if (!description) {
            e.preventDefault();
            alert('Введите описание калькуляции');
            return false;
        }
        
        if (!hasValidServices) {
            e.preventDefault();
            alert('Добавьте хотя бы одну услугу');
            return false;
        }
        
        // Проверяем, что все выбранные услуги имеют количество и цену
        let isValid = true;
        document.querySelectorAll('.service-row').forEach(row => {
            const serviceSelect = row.querySelector('.service-select');
            const quantity = row.querySelector('.quantity-input').value;
            const price = row.querySelector('.price-input').value;
            
            if (serviceSelect.value && (!quantity || !price)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Заполните количество и цену для всех выбранных услуг');
            return false;
        }
    });

    // Инициализация
    updateTotal();
});
</script>
