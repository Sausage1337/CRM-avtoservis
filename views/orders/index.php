<div class="container-fluid px-4">
    <!-- Hero Header -->
    <div class="orders-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Заказы</h1>
                <p class="hero-subtitle">Управление заказами автосервиса</p>
            </div>
            <div class="hero-actions">
                <a href="index.php?page=orders&action=create" class="btn-hero-primary">
                    <i class="fas fa-plus"></i>
                    <span>Создать заказ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= count($orders) ?></div>
                    <div class="stat-label">Всего заказов</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon stat-icon-success">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= count(array_filter($orders, fn($o) => $o['status'] === 'in_progress')) ?></div>
                    <div class="stat-label">В работе</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon stat-icon-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= count(array_filter($orders, fn($o) => $o['status'] === 'waiting_parts')) ?></div>
                    <div class="stat-label">Ожидают запчасти</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon stat-icon-info">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= count(array_filter($orders, fn($o) => $o['status'] === 'completed' && date('Y-m-d', strtotime($o['updated_at'] ?? $o['created_at'])) === date('Y-m-d'))) ?></div>
                    <div class="stat-label">Завершено сегодня</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters Panel -->
    <div class="search-panel">
        <div class="search-header">
            <i class="fas fa-search"></i>
            <span>Поиск и фильтры</span>
        </div>
        <div class="search-content">
            <form method="GET" action="index.php" class="search-form">
                <input type="hidden" name="page" value="orders">
                
                <div class="search-group">
                    <div class="search-field">
                        <label class="search-label">Поиск</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search-input" id="search" name="search" 
                                   value="<?= htmlspecialchars($search) ?>" 
                                   placeholder="Номер заказа, клиент, гос. номер...">
                        </div>
                    </div>
                    
                    <div class="search-field">
                        <label class="search-label">Статус</label>
                        <select class="search-select" id="status" name="status">
                            <option value="">Все статусы</option>
                            <?php foreach ($statuses as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($value) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="search-field">
                        <label class="search-label">Дата от</label>
                        <input type="date" class="search-input" id="date_from" name="date_from" 
                               value="<?= htmlspecialchars($date_from) ?>">
                    </div>
                    
                    <div class="search-field">
                        <label class="search-label">Дата до</label>
                        <input type="date" class="search-input" id="date_to" name="date_to" 
                               value="<?= htmlspecialchars($date_to) ?>">
                    </div>
                    
                    <div class="search-actions">
                        <button type="submit" class="btn-search-primary">
                            <i class="fas fa-search"></i>
                            <span>Найти</span>
                        </button>
                        <a href="index.php?page=orders" class="btn-search-secondary">
                            <i class="fas fa-times"></i>
                            <span>Сброс</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="orders-table-container">
        <div class="orders-table-header">
            <div class="table-title">
                <i class="fas fa-list"></i>
                <span>Список заказов</span>
            </div>
            <div class="table-counter">
                <span><?= count($orders) ?> заказов</span>
            </div>
        </div>

        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="empty-title">Заказы не найдены</h3>
                <p class="empty-description">Попробуйте изменить параметры поиска или создать новый заказ</p>
                <a href="index.php?page=orders&action=create" class="btn-hero-primary">
                    <i class="fas fa-plus"></i>
                    <span>Создать заказ</span>
                </a>
            </div>
        <?php else: ?>
            <div class="orders-table">
                <div class="orders-table-inner">
                    <table class="table-apple">
                        <thead>
                            <tr>
                                <th>№ Заказа</th>
                                <th>Клиент</th>
                                <th>Автомобиль</th>
                                <th>Описание</th>
                                <th>Статус</th>
                                        <th>Приоритет</th>
                                        <th>Сумма</th>
                                        <th>Дата создания</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <strong class="text-primary">
                                                    <?= htmlspecialchars($order['order_number'] ?? '#' . $order['id']) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong><?= htmlspecialchars($order['client_name']) ?></strong>
                                                    <small class="text-muted"><?= htmlspecialchars($order['client_phone']) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong><?= htmlspecialchars($order['brand'] . ' ' . $order['model']) ?></strong>
                                                    <small class="text-muted"><?= htmlspecialchars($order['license_plate']) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" 
                                                     title="<?= htmlspecialchars($order['notes'] ?? '') ?>">
                                                    <?= htmlspecialchars($order['notes'] ?? 'Описание не указано') ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = match($order['status']) {
                                                    'new' => 'bg-secondary',
                                                    'confirmed' => 'bg-info',
                                                    'in_progress' => 'bg-warning',
                                                    'waiting_parts' => 'bg-warning',
                                                    'completed' => 'bg-success',
                                                    'cancelled' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?= $statusClass ?>">
                                                    <?= htmlspecialchars($statuses[$order['status']] ?? $order['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $priority = $order['priority'] ?? 'normal';
                                                $priorityClass = match($priority) {
                                                    'low' => 'bg-success',
                                                    'normal' => 'bg-info',
                                                    'high' => 'bg-warning',
                                                    'urgent' => 'bg-danger',
                                                    default => 'bg-info'
                                                };
                                                $priorityText = match($priority) {
                                                    'low' => 'Низкий',
                                                    'normal' => 'Обычный',
                                                    'high' => 'Высокий',
                                                    'urgent' => 'Срочный',
                                                    default => 'Обычный'
                                                };
                                                ?>
                                                <span class="badge <?= $priorityClass ?>">
                                                    <?= htmlspecialchars($priorityText) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    <?= number_format($order['total_amount'] ?? $order['estimated_cost'] ?? 0, 0, ',', ' ') ?> ₽
                                                </strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?page=orders&action=view&id=<?= $order['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Просмотр">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="index.php?page=orders&action=edit&id=<?= $order['id'] ?>" 
                                                       class="btn btn-sm btn-outline-warning" title="Редактировать">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="showStatusModal(<?= $order['id'] ?>, '<?= htmlspecialchars($order['status']) ?>')"
                                                            title="Изменить статус">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                    <a href="index.php?page=orders&action=delete&id=<?= $order['id'] ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Вы уверены, что хотите удалить этот заказ?')"
                                                       title="Удалить">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Модальное окно изменения статуса -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Изменить статус заказа</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <input type="hidden" id="orderId" name="order_id">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">Новый статус</label>
                        <select class="form-select" id="newStatus" name="status" required>
                            <?php foreach ($statuses as $key => $value): ?>
                                <option value="<?= $key ?>"><?= htmlspecialchars($value) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Комментарий</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" 
                                  placeholder="Дополнительная информация о смене статуса"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Обновить статус</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showStatusModal(orderId, currentStatus) {
    document.getElementById('orderId').value = orderId;
    document.getElementById('newStatus').value = currentStatus;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('index.php?page=orders&action=updateStatus', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Показываем уведомление об успехе
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>${data.message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            document.body.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Закрываем модальное окно и перезагружаем страницу
            bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            alert('Ошибка: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при обновлении статуса');
    });
});
</script>

<style>
/* Orders Page Apple Design */
.orders-hero {
    background: linear-gradient(135deg, 
        rgba(0, 122, 255, 0.05) 0%, 
        rgba(0, 122, 255, 0.02) 100%);
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    padding: 40px;
    margin-bottom: 32px;
}

.hero-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 24px;
}

.hero-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--apple-black);
    margin: 0;
    letter-spacing: -0.5px;
}

.hero-subtitle {
    font-size: 18px;
    color: var(--apple-gray-6);
    margin: 8px 0 0 0;
    font-weight: 400;
}

.btn-hero-primary {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--apple-blue);
    color: white;
    padding: 12px 24px;
    border-radius: var(--apple-border-radius);
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(20px);
}

.btn-hero-primary:hover {
    background: #0056b3;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(0, 122, 255, 0.3);
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    padding: 24px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: var(--apple-gray-3);
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--apple-border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--apple-blue);
    color: white;
    font-size: 20px;
}

.stat-icon-success {
    background: var(--apple-green);
}

.stat-icon-warning {
    background: var(--apple-orange);
}

.stat-icon-info {
    background: var(--apple-blue);
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--apple-black);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: var(--apple-gray-6);
    font-weight: 500;
}

/* Search Panel */
.search-panel {
    background: white;
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    margin-bottom: 32px;
    overflow: hidden;
}

.search-header {
    background: var(--apple-gray);
    padding: 16px 24px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--apple-black);
    border-bottom: 1px solid var(--apple-gray-2);
}

.search-content {
    padding: 24px;
}

.search-group {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 20px;
    align-items: end;
}

.search-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.search-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--apple-black);
    margin: 0;
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--apple-gray-5);
    font-size: 14px;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    border: 1px solid var(--apple-gray-3);
    border-radius: var(--apple-border-radius);
    font-size: 16px;
    background: white;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.search-input:focus {
    outline: none;
    border-color: var(--apple-blue);
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
}

.search-select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--apple-gray-3);
    border-radius: var(--apple-border-radius);
    font-size: 16px;
    background: white;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.search-select:focus {
    outline: none;
    border-color: var(--apple-blue);
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
}

.search-actions {
    display: flex;
    gap: 12px;
}

.btn-search-primary {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--apple-blue);
    color: white;
    padding: 12px 20px;
    border-radius: var(--apple-border-radius);
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}

.btn-search-primary:hover {
    background: #0056b3;
    color: white;
    transform: translateY(-1px);
}

.btn-search-secondary {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--apple-gray);
    color: var(--apple-black);
    padding: 12px 20px;
    border-radius: var(--apple-border-radius);
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}

.btn-search-secondary:hover {
    background: var(--apple-gray-2);
    color: var(--apple-black);
}

/* Orders Table */
.orders-table-container {
    background: white;
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius-lg);
    overflow: hidden;
}

.orders-table-header {
    background: var(--apple-gray);
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--apple-gray-2);
}

.table-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 18px;
    color: var(--apple-black);
}

.table-counter {
    background: var(--apple-blue);
    color: white;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 14px;
    font-weight: 600;
}

.orders-table {
    overflow-x: auto;
}

.orders-table-inner {
    min-width: 1200px;
}

.table-apple {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table-apple thead th {
    background: var(--apple-gray);
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    color: var(--apple-black);
    border-bottom: 1px solid var(--apple-gray-2);
    white-space: nowrap;
}

.table-apple tbody td {
    padding: 20px;
    border-bottom: 1px solid var(--apple-gray-2);
    vertical-align: middle;
}

.table-apple tbody tr {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.table-apple tbody tr:hover {
    background: rgba(0, 122, 255, 0.02);
}

.table-apple tbody tr:last-child td {
    border-bottom: none;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 40px;
}

.empty-icon {
    font-size: 64px;
    color: var(--apple-gray-4);
    margin-bottom: 24px;
}

.empty-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--apple-black);
    margin: 0 0 12px 0;
}

.empty-description {
    font-size: 16px;
    color: var(--apple-gray-6);
    margin: 0 0 32px 0;
    max-width: 480px;
    margin-left: auto;
    margin-right: auto;
}

/* Responsive */
@media (max-width: 1200px) {
    .search-group {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .search-actions {
        justify-content: flex-start;
    }
}

@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .orders-hero {
        padding: 24px;
    }
    
    .hero-title {
        font-size: 28px;
    }
    
    .search-content {
        padding: 16px;
    }
    
    .orders-table-header {
        padding: 16px;
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
        text-align: center;
    }
}
</style>
