<div class="container-fluid">
    <!-- Заголовок страницы -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="create-client-header">
                <div class="d-flex align-items-center">
                    <div class="create-icon me-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h1 class="create-title mb-1">
                            <span class="text-success">Добавить</span> нового клиента
                        </h1>
                        <p class="create-subtitle mb-0">
                            <i class="fas fa-info-circle me-2"></i>Заполните информацию о новом клиенте
                        </p>
                    </div>
                </div>
                <div class="create-actions">
                    <a href="index.php?page=clients" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Назад к списку
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Форма создания клиента -->
    <div class="row">
        <div class="col-lg-8">
            <div class="create-client-card">
                <div class="create-client-card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Информация о клиенте
                    </h5>
                </div>
                <div class="create-client-card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Ошибки валидации:</h6>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?page=clients&action=create" id="createClientForm">
                        <!-- Основная информация -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">
                                    <i class="fas fa-user text-primary me-2"></i>ФИО *
                                </label>
                                <input type="text" class="form-control form-control-lg" id="full_name" 
                                       name="full_name" value="<?= htmlspecialchars($oldData['full_name'] ?? '') ?>" 
                                       placeholder="Иванов Иван Иванович" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Полное имя клиента обязательно для заполнения
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone text-success me-2"></i>Номер телефона *
                                </label>
                                <input type="tel" class="form-control form-control-lg" id="phone" 
                                       name="phone" value="<?= htmlspecialchars($oldData['phone'] ?? '') ?>" 
                                       placeholder="+7 (999) 123-45-67" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Основной способ связи с клиентом
                                </div>
                            </div>
                        </div>

                        <!-- Контактная информация -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope text-info me-2"></i>Email адрес
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" 
                                       name="email" value="<?= htmlspecialchars($oldData['email'] ?? '') ?>" 
                                       placeholder="client@example.com">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Для отправки уведомлений и документов
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">
                                    <i class="fas fa-birthday-cake text-warning me-2"></i>Дата рождения
                                </label>
                                <input type="date" class="form-control form-control-lg" id="birth_date" 
                                       name="birth_date" value="<?= htmlspecialchars($oldData['birth_date'] ?? '') ?>">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Для персональных предложений и скидок
                                </div>
                            </div>
                        </div>

                        <!-- Адрес -->
                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>Адрес
                            </label>
                            <textarea class="form-control form-control-lg" id="address" name="address" 
                                      rows="3" placeholder="Город, улица, дом, квартира..."><?= htmlspecialchars($oldData['address'] ?? '') ?></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Для доставки и выездных услуг
                            </div>
                        </div>

                        <!-- Дополнительная информация -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note text-secondary me-2"></i>Дополнительные заметки
                            </label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" 
                                      placeholder="Особые требования, предпочтения, замечания..."><?= htmlspecialchars($oldData['notes'] ?? '') ?></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Любая дополнительная информация о клиенте
                            </div>
                        </div>

                        <!-- Кнопки действий -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Создать клиента
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-undo me-2"></i>Очистить форму
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Информационная панель -->
            <div class="info-card">
                <div class="info-card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>О создании клиента
                    </h6>
                </div>
                <div class="info-card-body">
                    <div class="info-section mb-4">
                        <h6 class="text-primary">
                            <i class="fas fa-check-circle me-2"></i>Обязательные поля
                        </h6>
                        <ul class="info-list">
                            <li><strong>ФИО</strong> - полное имя клиента</li>
                            <li><strong>Телефон</strong> - основной способ связи</li>
                        </ul>
                    </div>

                    <div class="info-section mb-4">
                        <h6 class="text-success">
                            <i class="fas fa-plus-circle me-2"></i>Дополнительные поля
                        </h6>
                        <ul class="info-list">
                            <li><strong>Email</strong> - для уведомлений</li>
                            <li><strong>Дата рождения</strong> - для скидок</li>
                            <li><strong>Адрес</strong> - для доставки</li>
                            <li><strong>Заметки</strong> - дополнительная информация</li>
                        </ul>
                    </div>

                    <div class="info-section">
                        <h6 class="text-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Важно знать
                        </h6>
                        <ul class="info-list">
                            <li>Клиент будет доступен для создания заказов</li>
                            <li>Можно добавить автомобили клиента</li>
                            <li>История заказов будет отслеживаться</li>
                            <li>Данные можно редактировать позже</li>
                        </ul>
                    </div>
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
                        <a href="index.php?page=vehicles&action=create" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-car me-2"></i>Добавить автомобиль
                        </a>
                    </div>
                    <div class="quick-action-item">
                        <a href="index.php?page=process&action=client_booking" class="btn btn-outline-success w-100 mb-2">
                            <i class="fas fa-calendar-plus me-2"></i>Записать на обслуживание
                        </a>
                    </div>
                    <div class="quick-action-item">
                        <a href="index.php?page=process&action=calculation" class="btn btn-outline-info w-100">
                            <i class="fas fa-calculator me-2"></i>Создать калькуляцию
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для создания клиента */
.create-client-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.create-icon {
    background: rgba(255,255,255,0.2);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.create-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.create-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.create-actions .btn {
    font-size: 1.1rem;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
}

/* Карточка создания клиента */
.create-client-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: none;
}

.create-client-card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.create-client-card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #495057;
}

.create-client-card-body {
    padding: 2rem;
}

/* Форма */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.form-control-lg {
    font-size: 1.1rem;
}

.form-text {
    font-size: 0.9rem;
    color: #6c757d;
    margin-top: 0.5rem;
}

/* Кнопки */
.btn {
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838, #1e7e34);
}

/* Информационная карточка */
.info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    border: none;
}

.info-card-header {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
    padding: 1.5rem;
}

.info-card-header h6 {
    margin: 0;
    font-weight: 600;
}

.info-card-body {
    padding: 1.5rem;
}

.info-section {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
}

.info-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-section h6 {
    margin-bottom: 1rem;
    font-weight: 600;
}

.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
    font-size: 0.9rem;
    color: #6c757d;
}

.info-list li:last-child {
    border-bottom: none;
}

.info-list li strong {
    color: #495057;
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
    .create-client-header {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    
    .create-actions {
        margin-top: 1rem;
    }
    
    .create-title {
        font-size: 2rem;
    }
    
    .create-client-card-body {
        padding: 1.5rem;
    }
    
    .info-card-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .create-client-header {
        padding: 1rem;
    }
    
    .create-title {
        font-size: 1.5rem;
    }
    
    .create-client-card-body {
        padding: 1rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createClientForm');
    const phoneInput = document.getElementById('phone');
    
    // Маска для телефона
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 0) {
            if (value.length <= 1) {
                value = '+7 (' + value;
            } else if (value.length <= 4) {
                value = '+7 (' + value.substring(1, 4);
            } else if (value.length <= 7) {
                value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7);
            } else if (value.length <= 9) {
                value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7) + '-' + value.substring(7, 9);
            } else {
                value = '+7 (' + value.substring(1, 4) + ') ' + value.substring(4, 7) + '-' + value.substring(7, 9) + '-' + value.substring(9, 11);
            }
        }
        
        e.target.value = value;
    });
    
    // Валидация формы
    form.addEventListener('submit', function(e) {
        const fullName = document.getElementById('full_name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!fullName) {
            e.preventDefault();
            alert('Пожалуйста, введите ФИО клиента');
            document.getElementById('full_name').focus();
            return false;
        }
        
        if (!phone) {
            e.preventDefault();
            alert('Пожалуйста, введите номер телефона');
            document.getElementById('phone').focus();
            return false;
        }
        
        if (email && !isValidEmail(email)) {
            e.preventDefault();
            alert('Пожалуйста, введите корректный email адрес');
            document.getElementById('email').focus();
            return false;
        }
    });
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
</script>
