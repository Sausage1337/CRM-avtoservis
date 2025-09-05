<!-- Apple-style Service Edit Header -->
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="apple-card">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h1 class="h2 mb-2 fw-semibold text-dark">Редактирование услуги</h1>
                        <p class="text-secondary mb-0 fs-6">Изменение параметров услуги: <?= htmlspecialchars($service['name']) ?></p>
                    </div>
                    <a href="index.php?page=services" class="btn btn-apple-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Назад к списку
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Edit Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="apple-card">
                <form method="POST" action="index.php?page=services&action=edit&id=<?= $service['id'] ?>" class="service-edit-form">
                    
                    <!-- Service Basic Info Section -->
                    <div class="form-section p-4 border-bottom">
                        <h5 class="section-title mb-4">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Основная информация
                        </h5>
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name" class="form-label">Название услуги</label>
                                    <input type="text" 
                                           class="form-control-apple" 
                                           id="name" 
                                           name="name" 
                                           placeholder="Например: Замена масла"
                                           value="<?= htmlspecialchars($_POST['name'] ?? $service['name']) ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Описание услуги</label>
                                    <textarea class="form-control-apple" 
                                              id="description" 
                                              name="description" 
                                              rows="4"
                                              placeholder="Подробное описание услуги..."><?= htmlspecialchars($_POST['description'] ?? $service['description']) ?></textarea>
                                    <div class="form-help">Опишите что включает в себя данная услуга</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Duration Section -->
                    <div class="form-section p-4 border-bottom">
                        <h5 class="section-title mb-4">
                            <i class="fas fa-ruble-sign text-success me-2"></i>
                            Цена и время
                        </h5>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-label">Стоимость услуги</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control-apple" 
                                               id="price" 
                                               name="price" 
                                               min="0" 
                                               step="50"
                                               placeholder="0"
                                               value="<?= htmlspecialchars($_POST['price'] ?? $service['price']) ?>"
                                               required>
                                        <span class="input-group-text bg-light border-start-0">₽</span>
                                    </div>
                                    <div class="form-help">Цена за выполнение услуги</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration" class="form-label">Время выполнения</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control-apple" 
                                               id="duration" 
                                               name="duration" 
                                               min="5" 
                                               step="5"
                                               placeholder="60"
                                               value="<?= htmlspecialchars($_POST['duration'] ?? $service['duration']) ?>">
                                        <span class="input-group-text bg-light border-start-0">мин</span>
                                    </div>
                                    <div class="form-help">Примерное время выполнения работы</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Section -->
                    <div class="form-section p-4 border-bottom">
                        <h5 class="section-title mb-4">
                            <i class="fas fa-tags text-warning me-2"></i>
                            Категория
                        </h5>
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="category" class="form-label">Категория услуги</label>
                                    <div class="category-selector">
                                        <div class="d-flex gap-2">
                                            <select class="form-control-apple flex-grow-1" id="categorySelect" name="category">
                                                <option value="">Выберите категорию</option>
                                                <?php foreach ($categories as $cat): ?>
                                                    <option value="<?= htmlspecialchars($cat['category']) ?>"
                                                            <?= (($_POST['category'] ?? $service['category']) === $cat['category']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($cat['category']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="button" class="btn btn-apple-secondary" id="newCategoryBtn" title="Создать новую категорию">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <input type="text" 
                                               class="form-control-apple mt-3" 
                                               id="newCategory" 
                                               placeholder="Введите название новой категории..."
                                               style="display: none;">
                                        <div class="form-help">Группировка услуг по типам работ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="form-section p-4">
                        <h5 class="section-title mb-4">
                            <i class="fas fa-toggle-on text-info me-2"></i>
                            Статус услуги
                        </h5>
                        
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" 
                                       <?= (isset($_POST['active']) ? $_POST['active'] : $service['active']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="active">
                                    Активная услуга
                                </label>
                            </div>
                            <div class="form-help mt-2">Неактивные услуги не отображаются в формах создания заказов</div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="form-actions p-4 bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php?page=services" class="btn btn-apple-secondary px-4">
                                <i class="fas fa-times me-2"></i>Отмена
                            </a>
                            <button type="submit" class="btn btn-apple px-4">
                                <i class="fas fa-save me-2"></i>Сохранить изменения
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Service Edit Form Specific Styles */
.service-edit-form .form-section {
    position: relative;
}

.service-edit-form .section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--apple-black);
    margin-bottom: 24px;
    padding-bottom: 8px;
}

.service-edit-form .form-group {
    margin-bottom: 0;
}

.service-edit-form .form-label {
    font-size: 15px;
    font-weight: 500;
    color: var(--apple-black);
    margin-bottom: 8px;
    display: block;
}

.service-edit-form .form-help {
    font-size: 13px;
    color: var(--apple-gray-5);
    margin-top: 6px;
    line-height: 1.4;
}

/* Service Edit Input Groups */
.service-edit-form .input-group {
    position: relative;
}

.service-edit-form .input-group .form-control-apple {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
}

.service-edit-form .input-group-text {
    border: 1px solid var(--apple-gray-3);
    border-left: none;
    border-radius: 0 var(--apple-border-radius) var(--apple-border-radius) 0;
    background: var(--apple-gray);
    color: var(--apple-gray-6);
    font-size: 14px;
    padding: 16px 12px;
}

/* Service Edit Form Switch */
.service-edit-form .form-check.form-switch {
    padding-left: 0;
}

.service-edit-form .form-switch .form-check-input {
    width: 48px;
    height: 24px;
    border-radius: 12px;
    background-color: var(--apple-gray-3);
    border: none;
    position: relative;
    margin-right: 12px;
    transition: all 0.2s ease;
}

.service-edit-form .form-switch .form-check-input:checked {
    background-color: var(--apple-blue);
    border-color: var(--apple-blue);
}

.service-edit-form .form-switch .form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
}

.service-edit-form .form-check-label {
    font-size: 15px;
    font-weight: 500;
    color: var(--apple-black);
    cursor: pointer;
}

/* Service Edit Category Selector */
.service-edit-form .category-selector .d-flex {
    align-items: stretch;
}

.service-edit-form .category-selector select {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
}

.service-edit-form .category-selector button {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: 1px solid var(--apple-gray-3);
    min-width: 48px;
}

/* Service Edit Form Actions */
.service-edit-form .form-actions {
    background: var(--apple-gray);
    border-top: 1px solid var(--apple-gray-2);
}

/* Service Edit Clean borders */
.service-edit-form .border-bottom {
    border-color: var(--apple-gray-2) !important;
}

.service-edit-form .border-top {
    border-color: var(--apple-gray-2) !important;
}

/* Service Edit Responsive */
@media (max-width: 768px) {
    .service-edit-form .row.g-4 {
        --bs-gutter-x: 1rem;
    }
    
    .service-edit-form .form-section {
        padding: 1.5rem !important;
    }
    
    .service-edit-form .section-title {
        font-size: 16px;
        margin-bottom: 20px;
    }
    
    .service-edit-form .form-actions {
        padding: 1.5rem !important;
    }
    
    .service-edit-form .form-actions .d-flex {
        flex-direction: column;
        gap: 12px;
    }
    
    .service-edit-form .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('categorySelect');
    const newCategoryBtn = document.getElementById('newCategoryBtn');
    const newCategoryInput = document.getElementById('newCategory');
    
    newCategoryBtn.addEventListener('click', function() {
        if (newCategoryInput.style.display === 'none') {
            newCategoryInput.style.display = 'block';
            newCategoryInput.focus();
            categorySelect.disabled = true;
            this.innerHTML = '<i class="fas fa-times"></i>';
        } else {
            newCategoryInput.style.display = 'none';
            newCategoryInput.value = '';
            categorySelect.disabled = false;
            this.innerHTML = '<i class="fas fa-plus"></i>';
        }
    });
    
    newCategoryInput.addEventListener('input', function() {
        if (this.value.trim()) {
            categorySelect.value = '';
        }
    });
    
    // Передаем значение новой категории в основное поле
    document.querySelector('form').addEventListener('submit', function() {
        if (newCategoryInput.value.trim()) {
            categorySelect.value = newCategoryInput.value.trim();
        }
    });
});
</script>
