<div class="auth-header">
    <h1>
        <span class="avatar avatar-client me-2"><i class="fas fa-user"></i></span>
        Вход в личный кабинет
    </h1>
    <p class="mb-0 mt-2 opacity-75">Для доступа к вашим заказам и автомобилям</p>
</div>

<div class="auth-body">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=client_login">
        <div class="mb-3">
            <label for="phone" class="form-label">
                <i class="fas fa-phone me-2"></i>
                Номер телефона
            </label>
            <input type="tel" 
                   class="form-control" 
                   id="phone" 
                   name="phone" 
                   placeholder="+7 (999) 123-45-67"
                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="order_number" class="form-label">
                <i class="fas fa-hashtag me-2"></i>
                Номер заказа
            </label>
            <input type="text" 
                   class="form-control" 
                   id="order_number" 
                   name="order_number" 
                   placeholder="ORD-2024-001"
                   value="<?= htmlspecialchars($_POST['order_number'] ?? '') ?>"
                   required>
            <div class="form-text">
                <i class="fas fa-info-circle me-1"></i>
                Номер заказа указан в вашем заказ-наряде
            </div>
        </div>

        <button type="submit" class="btn btn-auth">
            <i class="fas fa-sign-in-alt me-2"></i>
            Войти в личный кабинет
        </button>
    </form>

    <div class="divider">
        <span>или</span>
    </div>

    <div class="text-center">
        <a href="index.php?page=client_register" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-user-plus me-2"></i>
            Зарегистрироваться
        </a>
    </div>

    <div class="auth-links">
        <a href="index.php?page=client_forgot_password">
            <i class="fas fa-question-circle me-1"></i>
            Забыли номер заказа?
        </a>
    </div>

    <div class="mt-4 p-3 bg-light rounded">
        <h6 class="text-muted mb-2">
            <i class="fas fa-lightbulb me-2"></i>
            Как войти в личный кабинет?
        </h6>
        <ol class="text-muted small mb-0">
            <li>Укажите номер телефона, который вы оставляли при записи</li>
            <li>Введите номер заказа из заказ-наряда</li>
            <li>Нажмите "Войти в личный кабинет"</li>
        </ol>
    </div>
</div>
