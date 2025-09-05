<div class="container-fluid">
    <!-- Заголовок страницы -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="clients-header">
                <div class="d-flex align-items-center">
                    <div class="clients-icon me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h1 class="clients-title mb-1">
                            <span class="text-primary">Управление</span> клиентами
                        </h1>
                        <p class="clients-subtitle mb-0">
                            <i class="fas fa-user-friends me-2"></i>База данных клиентов автосервиса
                        </p>
                    </div>
                </div>
                <div class="clients-actions">
                    <a href="index.php?page=clients&action=create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Добавить клиента
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Сообщения об успехе/ошибке -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Поиск и фильтры -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-card">
                <form method="GET" action="index.php" class="search-form">
                    <input type="hidden" name="page" value="clients">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" 
                                       value="<?= htmlspecialchars($search) ?>" 
                                       placeholder="Поиск по имени, телефону или email...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Найти
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="search-stats">
                                <span class="badge bg-info rounded-pill">
                                    <i class="fas fa-users me-1"></i>
                                    Всего клиентов: <?= count($clients) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Список клиентов -->
    <div class="row">
        <div class="col-12">
            <div class="clients-card">
                <div class="clients-card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Список клиентов
                    </h5>
                </div>
                <div class="clients-card-body">
                    <?php if (empty($clients)): ?>
                        <div class="empty-state">
                            <i class="fas fa-users fa-4x mb-3 text-muted"></i>
                            <h5 class="text-muted">Клиенты не найдены</h5>
                            <p class="text-muted">
                                <?php if (!empty($search)): ?>
                                    По запросу "<?= htmlspecialchars($search) ?>" ничего не найдено
                                <?php else: ?>
                                    В базе данных пока нет клиентов
                                <?php endif; ?>
                            </p>
                            <?php if (empty($search)): ?>
                                <a href="index.php?page=clients&action=create" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Добавить первого клиента
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-user me-1"></i>Клиент</th>
                                        <th><i class="fas fa-phone me-1"></i>Контакты</th>
                                        <th><i class="fas fa-car me-1"></i>Автомобили</th>
                                        <th><i class="fas fa-clipboard-list me-1"></i>Заказы</th>
                                        <th><i class="fas fa-ruble-sign me-1"></i>Потрачено</th>
                                        <th><i class="fas fa-calendar me-1"></i>Дата регистрации</th>
                                        <th><i class="fas fa-cogs me-1"></i>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $client): ?>
                                    <tr class="client-row">
                                        <td>
                                            <div class="client-info">
                                                <div class="client-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="client-details">
                                                    <div class="client-name">
                                                        <a href="index.php?page=clients&action=view&id=<?= $client['id'] ?>" 
                                                           class="client-link">
                                                            <?= htmlspecialchars($client['full_name']) ?>
                                                        </a>
                                                    </div>
                                                    <?php if ($client['address']): ?>
                                                    <div class="client-address">
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt me-1"></i>
                                                            <?= htmlspecialchars($client['address']) ?>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <div class="contact-phone">
                                                    <i class="fas fa-phone me-1 text-primary"></i>
                                                    <a href="tel:<?= htmlspecialchars($client['phone']) ?>" 
                                                       class="contact-link">
                                                        <?= htmlspecialchars($client['phone']) ?>
                                                    </a>
                                                </div>
                                                <?php if ($client['email']): ?>
                                                <div class="contact-email">
                                                    <i class="fas fa-envelope me-1 text-info"></i>
                                                    <a href="mailto:<?= htmlspecialchars($client['email']) ?>" 
                                                       class="contact-link">
                                                        <?= htmlspecialchars($client['email']) ?>
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="vehicles-info">
                                                <span class="badge bg-success rounded-pill">
                                                    <i class="fas fa-car me-1"></i>
                                                    <?= $client['vehicles_count'] ?>
                                                </span>
                                                <small class="text-muted d-block mt-1">
                                                    автомобилей
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders-info">
                                                <span class="badge bg-warning rounded-pill">
                                                    <i class="fas fa-clipboard-list me-1"></i>
                                                    <?= $client['orders_count'] ?>
                                                </span>
                                                <small class="text-muted d-block mt-1">
                                                    заказов
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="spending-info">
                                                <span class="spending-amount">
                                                    <?= number_format($client['total_spent'], 0, ',', ' ') ?> ₽
                                                </span>
                                                <?php if ($client['total_spent'] > 0): ?>
                                                <small class="text-success d-block">
                                                    <i class="fas fa-arrow-up me-1"></i>Активный клиент
                                                </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="registration-info">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?= date('d.m.Y', strtotime($client['created_at'])) ?>
                                                </small>
                                                <?php if ($client['birth_date']): ?>
                                                <br>
                                                <small class="text-info">
                                                    <i class="fas fa-birthday-cake me-1"></i>
                                                    <?= date('d.m.Y', strtotime($client['birth_date'])) ?>
                                                </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="index.php?page=clients&action=view&id=<?= $client['id'] ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="index.php?page=clients&action=edit&id=<?= $client['id'] ?>" 
                                                   class="btn btn-sm btn-outline-warning" title="Редактировать">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete(<?= $client['id'] ?>)" title="Удалить">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Подтверждение удаления
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Вы действительно хотите удалить этого клиента?</p>
                <p class="text-danger"><strong>Это действие нельзя отменить!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Отмена
                </button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Удалить
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для управления клиентами */
.clients-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.clients-icon {
    background: rgba(255,255,255,0.2);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.clients-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.clients-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.clients-actions .btn {
    font-size: 1.1rem;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
}

/* Карточка поиска */
.search-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    padding: 1.5rem;
}

.search-form .input-group-text {
    border: none;
    border-radius: 10px 0 0 10px;
}

.search-form .form-control {
    border: none;
    border-radius: 0;
    padding: 0.75rem 1rem;
}

.search-form .btn {
    border-radius: 0 10px 10px 0;
    padding: 0.75rem 1.5rem;
}

.search-stats {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.search-stats .badge {
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
}

/* Карточка клиентов */
.clients-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.clients-card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.clients-card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #495057;
}

.clients-card-body {
    padding: 1.5rem;
}

/* Таблица клиентов */
.table {
    margin-bottom: 0;
}

.table thead th {
    border-top: none;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    padding: 1rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

/* Информация о клиенте */
.client-info {
    display: flex;
    align-items: center;
}

.client-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    font-size: 1.2rem;
}

.client-details {
    flex: 1;
}

.client-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.client-link {
    color: #495057;
    text-decoration: none;
    font-weight: 600;
}

.client-link:hover {
    color: #667eea;
    text-decoration: underline;
}

.client-address {
    font-size: 0.85rem;
}

/* Контактная информация */
.contact-info {
    line-height: 1.6;
}

.contact-phone, .contact-email {
    margin-bottom: 0.25rem;
}

.contact-link {
    color: #495057;
    text-decoration: none;
}

.contact-link:hover {
    text-decoration: underline;
}

/* Информация об автомобилях и заказах */
.vehicles-info, .orders-info {
    text-align: center;
}

.vehicles-info .badge, .orders-info .badge {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

/* Информация о тратах */
.spending-amount {
    font-size: 1.1rem;
    font-weight: 600;
    color: #28a745;
}

/* Информация о регистрации */
.registration-info {
    line-height: 1.4;
}

/* Кнопки действий */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-buttons .btn {
    width: 35px;
    height: 35px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

/* Пустое состояние */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state i {
    opacity: 0.3;
}

.empty-state h5 {
    margin-bottom: 1rem;
}

.empty-state p {
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Адаптивность */
@media (max-width: 768px) {
    .clients-header {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    
    .clients-actions {
        margin-top: 1rem;
    }
    
    .clients-title {
        font-size: 2rem;
    }
    
    .search-stats {
        margin-top: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .action-buttons .btn {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .clients-header {
        padding: 1rem;
    }
    
    .clients-title {
        font-size: 1.5rem;
    }
    
    .search-card {
        padding: 1rem;
    }
    
    .clients-card-body {
        padding: 1rem;
    }
    
    .client-avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
        margin-right: 0.75rem;
    }
}
</style>

<script>
function confirmDelete(clientId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    confirmBtn.href = `index.php?page=clients&action=delete&id=${clientId}`;
    modal.show();
}

// Автоматическое скрытие сообщений об успехе/ошибке
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('.alert-success');
    const errorMessage = document.querySelector('.alert-danger');
    
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }, 3000);
    }
    
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.opacity = '0';
            setTimeout(() => errorMessage.remove(), 300);
        }, 5000);
    }
});
</script>
