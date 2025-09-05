<?php
if (!isset($contactInfo)) {
    $contactInfo = [
        'phone' => '+7 (999) 123-45-67',
        'email' => 'info@autoservice.ru',
        'address' => 'г. Москва, ул. Автомобильная, д. 123',
        'hours' => [
            'mon_fri' => '08:00 - 20:00',
            'saturday' => '09:00 - 18:00',
            'sunday' => '10:00 - 16:00'
        ]
    ];
}
?>

<div class="contact-page">
    <!-- Заголовок -->
    <div class="contact-header">
        <div class="contact-hero">
            <div class="hero-content">
                <h1 class="hero-title">Контакты</h1>
                <p class="hero-subtitle">Мы всегда готовы помочь с вашим автомобилем</p>
            </div>
            <div class="hero-actions">
                <button class="btn-hero-primary" onclick="openCallbackModal()">
                    <i class="fas fa-phone"></i>
                    <span>Заказать звонок</span>
                </button>
                <button class="btn-hero-secondary" onclick="openChatWidget()">
                    <i class="fas fa-comments"></i>
                    <span>Написать в чат</span>
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Основная контактная информация -->
        <div class="col-lg-8">
            <div class="row g-4">
                <!-- Контактные методы -->
                <div class="col-12">
                    <div class="contact-methods">
                        <div class="contact-method">
                            <div class="method-icon phone-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="method-content">
                                <h3 class="method-title">Позвонить нам</h3>
                                <p class="method-value">
                                    <a href="tel:<?= str_replace([' ', '(', ')', '-'], '', $contactInfo['phone']) ?>">
                                        <?= htmlspecialchars($contactInfo['phone']) ?>
                                    </a>
                                </p>
                                <span class="method-description">Бесплатная консультация</span>
                            </div>
                            <div class="method-action">
                                <a href="tel:<?= str_replace([' ', '(', ')', '-'], '', $contactInfo['phone']) ?>" class="action-btn">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="contact-method">
                            <div class="method-icon email-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="method-content">
                                <h3 class="method-title">Написать письмо</h3>
                                <p class="method-value">
                                    <a href="mailto:<?= htmlspecialchars($contactInfo['email']) ?>">
                                        <?= htmlspecialchars($contactInfo['email']) ?>
                                    </a>
                                </p>
                                <span class="method-description">Ответим в течение часа</span>
                            </div>
                            <div class="method-action">
                                <a href="mailto:<?= htmlspecialchars($contactInfo['email']) ?>" class="action-btn">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Адрес и график -->
                <div class="col-md-6">
                    <div class="info-card address-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="info-title">Адрес сервиса</h3>
                        </div>
                        <div class="info-content">
                            <p class="address-text"><?= htmlspecialchars($contactInfo['address']) ?></p>
                            <div class="address-features">
                                <div class="feature">
                                    <i class="fas fa-car"></i>
                                    <span>Бесплатная парковка</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-wifi"></i>
                                    <span>Wi-Fi в зоне ожидания</span>
                                </div>
                            </div>
                            <button class="info-action-btn" onclick="openMap()">
                                <i class="fas fa-map"></i>
                                <span>Показать маршрут</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-card schedule-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="info-title">График работы</h3>
                        </div>
                        <div class="info-content">
                            <div class="schedule-list">
                                <div class="schedule-item">
                                    <span class="schedule-day">Пн - Пт</span>
                                    <span class="schedule-time"><?= htmlspecialchars($contactInfo['hours']['mon_fri']) ?></span>
                                </div>
                                <div class="schedule-item">
                                    <span class="schedule-day">Суббота</span>
                                    <span class="schedule-time"><?= htmlspecialchars($contactInfo['hours']['saturday']) ?></span>
                                </div>
                                <div class="schedule-item">
                                    <span class="schedule-day">Воскресенье</span>
                                    <span class="schedule-time"><?= htmlspecialchars($contactInfo['hours']['sunday']) ?></span>
                                </div>
                            </div>
                            <div class="current-status">
                                <span class="status-indicator online"></span>
                                <span class="status-text">Сейчас открыто</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Боковая панель -->
        <div class="col-lg-4">
            <div class="sidebar-widgets">
                <!-- Быстрые действия -->
                <div class="widget-card actions-widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Быстрые действия</h3>
                        <p class="widget-subtitle">Выберите удобный способ связи</p>
                    </div>
                    <div class="widget-content">
                        <div class="action-buttons">
                            <button class="quick-action-btn primary" onclick="openCallbackModal()">
                                <div class="action-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="action-content">
                                    <span class="action-title">Обратный звонок</span>
                                    <span class="action-desc">Перезвоним за 5 минут</span>
                                </div>
                            </button>
                            
                            <a href="index.php?page=orders&action=create" class="quick-action-btn">
                                <div class="action-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="action-content">
                                    <span class="action-title">Записаться на сервис</span>
                                    <span class="action-desc">Онлайн-запись</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Экстренные услуги -->
                <div class="widget-card emergency-widget">
                    <div class="emergency-header">
                        <div class="emergency-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="emergency-content">
                            <h3 class="emergency-title">Экстренная помощь</h3>
                            <p class="emergency-subtitle">24/7 на дороге</p>
                        </div>
                    </div>
                    <div class="emergency-services">
                        <div class="service-item">
                            <i class="fas fa-truck"></i>
                            <span>Эвакуатор</span>
                        </div>
                        <div class="service-item">
                            <i class="fas fa-tools"></i>
                            <span>Выездной ремонт</span>
                        </div>
                        <div class="service-item">
                            <i class="fas fa-battery-half"></i>
                            <span>Прикурить авто</span>
                        </div>
                    </div>
                    <a href="tel:+79991234568" class="emergency-call-btn">
                        <i class="fas fa-phone"></i>
                        <span>Вызвать экстренную помощь</span>
                    </a>
                </div>

                <!-- Преимущества -->
                <div class="widget-card benefits-widget">
                    <div class="widget-header">
                        <h3 class="widget-title">Почему выбирают нас</h3>
                    </div>
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="benefit-content">
                                <span class="benefit-title">Гарантия 2 года</span>
                                <span class="benefit-desc">На все виды работ</span>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="benefit-content">
                                <span class="benefit-title">Сертифицированные мастера</span>
                                <span class="benefit-desc">Опыт более 10 лет</span>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="benefit-content">
                                <span class="benefit-title">Оригинальные запчасти</span>
                                <span class="benefit-desc">Прямые поставки</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно заказа звонка -->
<div class="modal fade" id="callbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Заказать обратный звонок</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="callbackForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="callbackName" class="form-label">Ваше имя</label>
                        <input type="text" class="form-control form-control-apple" id="callbackName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="callbackPhone" class="form-label">Телефон</label>
                        <input type="tel" class="form-control form-control-apple" id="callbackPhone" name="phone" 
                               placeholder="+7 (999) 123-45-67" required>
                    </div>
                    <div class="mb-3">
                        <label for="callbackBestTime" class="form-label">Удобное время для звонка</label>
                        <select class="form-select form-control-apple" id="callbackBestTime" name="best_time">
                            <option value="">Любое время</option>
                            <option value="morning">Утром (9:00 - 12:00)</option>
                            <option value="afternoon">Днём (12:00 - 17:00)</option>
                            <option value="evening">Вечером (17:00 - 20:00)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="callbackMessage" class="form-label">Сообщение (необязательно)</label>
                        <textarea class="form-control form-control-apple" id="callbackMessage" name="message" rows="3"
                                  placeholder="Опишите ваш вопрос или проблему"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-apple" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-apple-primary">
                        <i class="fas fa-phone me-2"></i>
                        Заказать звонок
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Чат виджет -->
<div id="chatWidget" class="chat-widget">
    <div class="chat-toggle" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
        <span class="chat-badge" id="chatBadge" style="display: none;">1</span>
    </div>
    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <div class="avatar me-2">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <div class="chat-title">Поддержка</div>
                    <div class="chat-status">Онлайн</div>
                </div>
            </div>
            <button class="chat-close" onclick="toggleChat()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message operator">
                <div class="message-content">
                    Здравствуйте! Как дела с вашим автомобилем? Чем могу помочь?
                </div>
                <div class="message-time"><?= date('H:i') ?></div>
            </div>
        </div>
        <div class="chat-input-wrapper">
            <form id="chatForm">
                <div class="chat-input">
                    <input type="text" id="chatMessageInput" placeholder="Напишите сообщение..." required>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Contact Page New Design */
.contact-page {
    padding: 0;
}

/* Hero Section */
.contact-header {
    background: linear-gradient(135deg, 
        rgba(247, 247, 247, 0.8) 0%, 
        rgba(255, 255, 255, 0.9) 100%);
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    padding: 40px;
    margin-bottom: 32px;
    color: var(--apple-black);
}

.contact-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 32px;
}

.hero-title {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 8px 0;
    letter-spacing: -0.02em;
}

.hero-subtitle {
    font-size: 18px;
    margin: 0;
    color: var(--apple-gray-6);
    font-weight: 400;
}

.hero-actions {
    display: flex;
    gap: 12px;
    flex-shrink: 0;
}

.btn-hero-primary,
.btn-hero-secondary {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: var(--apple-border-radius-lg);
    border: none;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
}

.btn-hero-primary {
    background: white;
    color: var(--apple-blue);
}

.btn-hero-primary:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-1px);
}

.btn-hero-secondary {
    background: var(--apple-gray);
    color: var(--apple-black);
    border: 1px solid var(--apple-gray-3);
}

.btn-hero-secondary:hover {
    background: var(--apple-gray-2);
    color: var(--apple-black);
}

/* Contact Methods */
.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.contact-method {
    background: white;
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.2s ease;
}

.contact-method:hover {
    border-color: var(--apple-blue);
    box-shadow: 0 4px 20px rgba(0, 122, 255, 0.1);
    transform: translateY(-2px);
}

.method-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--apple-border-radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.phone-icon {
    background: linear-gradient(135deg, #34C759, #30A84C);
}

.email-icon {
    background: linear-gradient(135deg, var(--apple-blue), var(--apple-blue-dark));
}

.method-content {
    flex: 1;
}

.method-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--apple-black);
    margin: 0 0 4px 0;
}

.method-value {
    margin: 0 0 4px 0;
}

.method-value a {
    color: var(--apple-black);
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
}

.method-value a:hover {
    color: var(--apple-blue);
}

.method-description {
    font-size: 14px;
    color: var(--apple-gray-5);
}

.method-action {
    flex-shrink: 0;
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--apple-gray);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--apple-gray-6);
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-btn:hover {
    background: var(--apple-blue);
    color: white;
    transform: scale(1.1);
}

/* Info Cards */
.info-card {
    background: white;
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    padding: 24px;
    height: 100%;
    transition: all 0.2s ease;
}

.info-card:hover {
    border-color: var(--apple-blue);
    box-shadow: 0 4px 20px rgba(0, 122, 255, 0.1);
}

.info-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--apple-border-radius-lg);
    background: linear-gradient(135deg, var(--apple-blue), var(--apple-blue-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.info-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--apple-black);
    margin: 0;
}

.address-text {
    font-size: 16px;
    color: var(--apple-black);
    margin-bottom: 16px;
    line-height: 1.5;
}

.address-features {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.feature {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--apple-gray-6);
}

.feature i {
    color: var(--apple-blue);
    width: 16px;
}

.info-action-btn {
    background: var(--apple-blue);
    color: white;
    border: none;
    border-radius: var(--apple-border-radius);
    padding: 12px 20px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.info-action-btn:hover {
    background: var(--apple-blue-dark);
    transform: translateY(-1px);
}

/* Schedule */
.schedule-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}

.schedule-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.schedule-day {
    font-weight: 500;
    color: var(--apple-black);
}

.schedule-time {
    color: var(--apple-blue);
    font-weight: 600;
}

.current-status {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: var(--apple-gray);
    border-radius: var(--apple-border-radius);
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-indicator.online {
    background: #34C759;
}

.status-text {
    font-size: 14px;
    color: var(--apple-gray-6);
    font-weight: 500;
}

/* Sidebar Widgets */
.sidebar-widgets {
    display: flex;
    flex-direction: column;
    gap: 24px;
    position: sticky;
    top: 20px;
}

.widget-card {
    background: white;
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    padding: 24px;
    transition: all 0.2s ease;
}

.widget-card:hover {
    border-color: var(--apple-blue);
    box-shadow: 0 4px 20px rgba(0, 122, 255, 0.1);
}

.widget-header {
    margin-bottom: 20px;
}

.widget-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--apple-black);
    margin: 0 0 4px 0;
}

.widget-subtitle {
    font-size: 14px;
    color: var(--apple-gray-5);
    margin: 0;
}

/* Quick Actions */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--apple-gray);
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
    cursor: pointer;
}

.quick-action-btn:hover {
    background: var(--apple-blue);
    border-color: var(--apple-blue);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 122, 255, 0.25);
}

.quick-action-btn.primary {
    background: var(--apple-blue);
    border-color: var(--apple-blue);
    color: white;
}

.quick-action-btn.primary:hover {
    background: var(--apple-blue-dark);
    border-color: var(--apple-blue-dark);
    color: white;
}

.action-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--apple-border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.quick-action-btn:not(.primary) .action-icon {
    background: white;
    color: var(--apple-blue);
}

.quick-action-btn:hover:not(.primary) .action-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.action-content {
    flex: 1;
    text-align: left;
}

.action-title {
    display: block;
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 2px;
}

.action-desc {
    display: block;
    font-size: 13px;
    opacity: 0.8;
}

/* Emergency Widget */
.emergency-widget {
    border-color: #FF3B30;
    background: linear-gradient(135deg, #FF3B30 0%, #D70015 100%);
    color: white;
}

.emergency-widget:hover {
    border-color: #FF3B30;
    box-shadow: 0 4px 20px rgba(255, 59, 48, 0.25);
}

.emergency-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.emergency-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--apple-border-radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.emergency-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 2px 0;
}

.emergency-subtitle {
    font-size: 14px;
    margin: 0;
    opacity: 0.9;
}

.emergency-services {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.service-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.service-item i {
    width: 16px;
    opacity: 0.9;
}

.emergency-call-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: white;
    color: #FF3B30;
    border: none;
    border-radius: var(--apple-border-radius);
    padding: 12px 20px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.emergency-call-btn:hover {
    background: rgba(255, 255, 255, 0.9);
    color: #FF3B30;
    transform: translateY(-1px);
}

/* Benefits Widget */
.benefits-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.benefit-icon {
    width: 40px;
    height: 40px;
    background: var(--apple-gray);
    border-radius: var(--apple-border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--apple-blue);
    font-size: 16px;
    flex-shrink: 0;
}

.benefit-content {
    flex: 1;
}

.benefit-title {
    display: block;
    font-weight: 600;
    color: var(--apple-black);
    font-size: 14px;
    margin-bottom: 2px;
}

.benefit-desc {
    display: block;
    font-size: 12px;
    color: var(--apple-gray-5);
}

/* Chat Widget Styles */
.chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.chat-toggle {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--apple-blue), var(--apple-blue-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: var(--apple-shadow-lg);
    transition: all 0.3s ease;
    position: relative;
}

.chat-toggle:hover {
    transform: scale(1.05);
    box-shadow: var(--apple-shadow-xl);
}

.chat-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff3b30;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-window {
    position: absolute;
    bottom: 70px;
    right: 0;
    width: 350px;
    height: 400px;
    background: white;
    border-radius: var(--apple-border-radius-lg);
    box-shadow: var(--apple-shadow-xl);
    display: none;
    flex-direction: column;
    overflow: hidden;
}

.chat-window.open {
    display: flex;
}

.chat-header {
    background: linear-gradient(135deg, var(--apple-blue), var(--apple-blue-dark));
    color: white;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chat-header .avatar {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.chat-title {
    font-weight: 600;
    font-size: 14px;
}

.chat-status {
    font-size: 12px;
    opacity: 0.8;
}

.chat-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.chat-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.chat-messages {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.message {
    display: flex;
    flex-direction: column;
    max-width: 80%;
}

.message.operator {
    align-self: flex-start;
}

.message.client {
    align-self: flex-end;
}

.message-content {
    background: var(--apple-gray);
    padding: 8px 12px;
    border-radius: var(--apple-border-radius);
    font-size: 14px;
    line-height: 1.4;
}

.message.client .message-content {
    background: var(--apple-blue);
    color: white;
}

.message-time {
    font-size: 11px;
    color: var(--apple-gray-5);
    margin-top: 4px;
    align-self: flex-end;
}

.chat-input-wrapper {
    padding: 16px;
    border-top: 1px solid var(--apple-gray-2);
}

.chat-input {
    display: flex;
    gap: 8px;
}

.chat-input input {
    flex: 1;
    border: 1px solid var(--apple-gray-3);
    border-radius: var(--apple-border-radius);
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
}

.chat-input input:focus {
    border-color: var(--apple-blue);
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
}

.chat-input button {
    background: var(--apple-blue);
    color: white;
    border: none;
    border-radius: var(--apple-border-radius);
    padding: 8px 12px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.chat-input button:hover {
    background: var(--apple-blue-dark);
}

/* Responsive */
@media (max-width: 768px) {
    .contact-header {
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .contact-hero {
        flex-direction: column;
        text-align: center;
        gap: 24px;
    }
    
    .hero-title {
        font-size: 28px;
    }
    
    .hero-subtitle {
        font-size: 16px;
    }
    
    .hero-actions {
        width: 100%;
        justify-content: center;
    }
    
    .btn-hero-primary,
    .btn-hero-secondary {
        flex: 1;
        justify-content: center;
    }
    
    .contact-method {
        padding: 20px;
    }
    
    .method-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }
    
    .method-title {
        font-size: 16px;
    }
    
    .info-card {
        padding: 20px;
    }
    
    .info-header {
        gap: 12px;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .info-title {
        font-size: 16px;
    }
    
    .sidebar-widgets {
        position: static;
        gap: 20px;
    }
    
    .widget-card {
        padding: 20px;
    }
    
    .widget-title {
        font-size: 16px;
    }
    
    .quick-action-btn {
        padding: 14px;
        gap: 12px;
    }
    
    .action-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
    
    .emergency-header {
        gap: 12px;
    }
    
    .emergency-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .emergency-title {
        font-size: 16px;
    }
    
    .chat-window {
        width: calc(100vw - 40px);
        height: 300px;
        right: -10px;
    }
}

@media (max-width: 576px) {
    .hero-actions {
        flex-direction: column;
    }
    
    .contact-method {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .method-action {
        align-self: center;
    }
}
</style>

<script>
let chatSessionId = '<?= session_id() ?>';
let chatOpen = false;

// Callback form
document.getElementById('callbackForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Отправка...';
    submitBtn.disabled = true;
    
    fetch('index.php?page=contact&action=callback', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById('callbackModal')).hide();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Произошла ошибка при отправке заявки');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Chat functionality
function toggleChat() {
    const chatWindow = document.getElementById('chatWindow');
    chatOpen = !chatOpen;
    
    if (chatOpen) {
        chatWindow.classList.add('open');
        loadChatMessages();
    } else {
        chatWindow.classList.remove('open');
    }
}

function openChatWidget() {
    if (!chatOpen) {
        toggleChat();
    }
}

function openCallbackModal() {
    const modal = new bootstrap.Modal(document.getElementById('callbackModal'));
    modal.show();
}

function openMap() {
    const address = "<?= htmlspecialchars($contactInfo['address']) ?>";
    const encodedAddress = encodeURIComponent(address);
    window.open(`https://yandex.ru/maps/?text=${encodedAddress}`, '_blank');
}

// Chat form
document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const input = document.getElementById('chatMessageInput');
    const message = input.value.trim();
    
    if (message) {
        addChatMessage(message, 'client');
        input.value = '';
        
        const formData = new FormData();
        formData.append('message', message);
        formData.append('session_id', chatSessionId);
        
        fetch('index.php?page=contact&action=chat', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.auto_response) {
                setTimeout(() => {
                    addChatMessage(data.auto_response, 'operator');
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }
});

function addChatMessage(content, sender) {
    const messagesContainer = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}`;
    
    const now = new Date();
    const time = now.getHours().toString().padStart(2, '0') + ':' + 
                 now.getMinutes().toString().padStart(2, '0');
    
    messageDiv.innerHTML = `
        <div class="message-content">${content}</div>
        <div class="message-time">${time}</div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function loadChatMessages() {
    fetch(`index.php?page=contact&action=getChatMessages&session_id=${chatSessionId}`)
    .then(response => response.json())
    .then(data => {
        const messagesContainer = document.getElementById('chatMessages');
        // Clear existing messages except welcome message
        const welcomeMessage = messagesContainer.querySelector('.message.operator');
        messagesContainer.innerHTML = '';
        messagesContainer.appendChild(welcomeMessage);
        
        // Add loaded messages
        data.messages.forEach(msg => {
            if (msg.sender_type === 'client') {
                addChatMessage(msg.message, 'client');
            }
        });
    })
    .catch(error => {
        console.error('Error loading messages:', error);
    });
}

// Phone input formatting
document.getElementById('callbackPhone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        if (value[0] === '8') value = '7' + value.slice(1);
        if (value[0] !== '7') value = '7' + value;
        
        let formatted = '+7';
        if (value.length > 1) formatted += ' (' + value.slice(1, 4);
        if (value.length > 4) formatted += ') ' + value.slice(4, 7);
        if (value.length > 7) formatted += '-' + value.slice(7, 9);
        if (value.length > 9) formatted += '-' + value.slice(9, 11);
        
        e.target.value = formatted;
    }
});

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.contact-page');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const alertInstance = new bootstrap.Alert(alert);
            alertInstance.close();
        }
    }, 5000);
}
</script>
