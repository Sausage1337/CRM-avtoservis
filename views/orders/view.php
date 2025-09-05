<div class="container-fluid">
    <!-- Заголовок страницы -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-eye text-primary"></i> Просмотр заказа
                    </h1>
                    <p class="text-muted mb-0">Заказ №<?= htmlspecialchars($order['order_number'] ?? '#' . $order['id']) ?></p>
                </div>
                <div>
                    <a href="index.php?page=orders&action=edit&id=<?= $order['id'] ?>" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>
                    <a href="index.php?page=orders" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад к списку
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Основная информация о заказе -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list"></i> Информация о заказе
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Номер заказа</label>
                                <div class="form-control-plaintext">
                                    <strong class="text-primary"><?= htmlspecialchars($order['order_number'] ?? '#' . $order['id']) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Статус</label>
                                <div>
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
                                    $statusText = match($order['status']) {
                                        'new' => 'Новый',
                                        'confirmed' => 'Подтвержден',
                                        'in_progress' => 'В работе',
                                        'waiting_parts' => 'Ожидает запчасти',
                                        'completed' => 'Завершен',
                                        'cancelled' => 'Отменен',
                                        default => $order['status']
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?> fs-6"><?= htmlspecialchars($statusText) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Приоритет</label>
                                <div>
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
                                    <span class="badge <?= $priorityClass ?>"><?= htmlspecialchars($priorityText) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Предварительная стоимость</label>
                                <div class="form-control-plaintext">
                                    <strong class="text-success fs-5"><?= number_format($order['estimated_cost'] ?? 0, 0, ',', ' ') ?> ₽</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Описание работ</label>
                        <div class="form-control-plaintext">
                            <?= htmlspecialchars($order['notes'] ?? 'Описание не указано') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Дата создания</label>
                                <div class="form-control-plaintext">
                                    <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($order['updated_at']) && $order['updated_at'] !== $order['created_at']): ?>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Последнее обновление</label>
                                <div class="form-control-plaintext">
                                    <?= date('d.m.Y H:i', strtotime($order['updated_at'])) ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика заказа -->
        <div class="col-md-4">
            <div class="card border-primary shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar"></i> Статистика
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Количество услуг:</span>
                        <span class="badge bg-info"><?= count($orderItems) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Общая стоимость:</span>
                        <span class="badge bg-success">
                            <?= number_format(array_sum(array_column($orderItems, 'price')), 0, ',', ' ') ?> ₽
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Изменений статуса:</span>
                        <span class="badge bg-warning"><?= count($orderHistory) ?></span>
                    </div>
                    <hr>
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                onclick="showStatusModal(<?= $order['id'] ?>, '<?= htmlspecialchars($order['status']) ?>')">
                            <i class="fas fa-exchange-alt"></i> Изменить статус
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Информация о клиенте и автомобиле -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user"></i> Информация о клиенте
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">ФИО</label>
                        <div class="form-control-plaintext">
                            <strong><?= htmlspecialchars($order['client_name']) ?></strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Телефон</label>
                        <div class="form-control-plaintext">
                            <a href="tel:<?= htmlspecialchars($order['client_phone']) ?>" class="text-decoration-none">
                                <?= htmlspecialchars($order['client_phone']) ?>
                            </a>
                        </div>
                    </div>
                    <?php if ($order['client_email']): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email</label>
                        <div class="form-control-plaintext">
                            <a href="mailto:<?= htmlspecialchars($order['client_email']) ?>" class="text-decoration-none">
                                <?= htmlspecialchars($order['client_email']) ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-car"></i> Информация об автомобиле
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Марка и модель</label>
                        <div class="form-control-plaintext">
                            <strong><?= htmlspecialchars($order['brand'] . ' ' . $order['model']) ?></strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Гос. номер</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-dark fs-6"><?= htmlspecialchars($order['license_plate']) ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Год выпуска</label>
                                <div class="form-control-plaintext">
                                    <?= htmlspecialchars($order['year'] ?? 'Не указан') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Цвет</label>
                                <div class="form-control-plaintext">
                                    <?= htmlspecialchars($order['color'] ?? 'Не указан') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($order['vin']): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted">VIN</label>
                        <div class="form-control-plaintext">
                            <code><?= htmlspecialchars($order['vin']) ?></code>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Услуги и запчасти заказа -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools"></i> Услуги заказа
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($orderItems)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-tools fa-2x text-muted mb-3"></i>
                            <p class="text-muted">К заказу не привязаны услуги</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Услуга</th>
                                        <th>Описание</th>
                                        <th>Количество</th>
                                        <th>Цена за единицу</th>
                                        <th>Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($item['service_name']) ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($item['service_description'] ?: 'Описание отсутствует') ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?= $item['quantity'] ?></span>
                                            </td>
                                            <td>
                                                <?= number_format($item['price'], 0, ',', ' ') ?> ₽
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    <?= number_format($item['price'] * $item['quantity'], 0, ',', ' ') ?> ₽
                                                </strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Итого:</th>
                                        <th class="text-success">
                                            <?= number_format(array_sum(array_column($orderItems, 'price')), 0, ',', ' ') ?> ₽
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i> Запчасти</h6>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addPartModal"><i class="fas fa-plus"></i> Добавить</button>
                </div>
                <div class="card-body">
                    <div id="orderPartsContainer" class="small text-muted">Загрузка...</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPartModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить запчасть</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Позиция склада</label>
                        <select id="partSelect" class="form-select"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Количество</label>
                        <input id="partQty" type="number" min="1" value="1" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="savePartBtn">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- История изменений статуса -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> История изменений статуса
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($orderHistory)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x text-muted mb-3"></i>
                            <p class="text-muted">История изменений отсутствует</p>
                        </div>
                    <?php else: ?>
                        <div class="timeline">
                            <?php foreach ($orderHistory as $index => $history): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker <?= $index === 0 ? 'timeline-marker-primary' : 'timeline-marker-secondary' ?>"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    <?php
                                                    $statusText = match($history['status']) {
                                                        'new' => 'Новый',
                                                        'confirmed' => 'Подтвержден',
                                                        'in_progress' => 'В работе',
                                                        'waiting_parts' => 'Ожидает запчасти',
                                                        'completed' => 'Завершен',
                                                        'cancelled' => 'Отменен',
                                                        default => $history['status']
                                                    };
                                                    ?>
                                                    <?= htmlspecialchars($statusText) ?>
                                                </h6>
                                                <?php if ($history['comment']): ?>
                                                    <p class="text-muted mb-1"><?= htmlspecialchars($history['comment']) ?></p>
                                                <?php endif; ?>
                                                <small class="text-muted">
                                                    <?= date('d.m.Y H:i', strtotime($history['created_at'])) ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($history['updated_by_name'] ?? 'Система') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
                            <option value="new">Новый</option>
                            <option value="confirmed">Подтвержден</option>
                            <option value="in_progress">В работе</option>
                            <option value="waiting_parts">Ожидает запчасти</option>
                            <option value="completed">Завершен</option>
                            <option value="cancelled">Отменен</option>
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

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #e3e6f0;
}

.timeline-marker-primary {
    background-color: #4e73df;
}

.timeline-marker-secondary {
    background-color: #858796;
}

.timeline-content {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #4e73df;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -29px;
    top: 24px;
    width: 2px;
    height: calc(100% + 6px);
    background-color: #e3e6f0;
}
</style>

<script>
const orderId = <?= (int)$order['id'] ?>;
function loadParts() {
    fetch('index.php?page=orders&action=listParts&order_id=' + orderId)
        .then(r => r.text())
        .then(html => { document.getElementById('orderPartsContainer').innerHTML = html; });
}
function loadPartOptions() {
    fetch('index.php?page=orders&action=inventoryOptions')
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('partSelect');
            sel.innerHTML = '';
            (data.items || []).forEach(i => {
                const opt = document.createElement('option');
                opt.value = i.id; opt.textContent = i.part_name;
                sel.appendChild(opt);
            });
        });
}
document.getElementById('savePartBtn').addEventListener('click', () => {
    const itemId = document.getElementById('partSelect').value;
    const qty = document.getElementById('partQty').value;
    const body = new FormData();
    body.append('order_id', orderId);
    body.append('item_id', itemId);
    body.append('quantity', qty);
    fetch('index.php?page=orders&action=addPart', { method: 'POST', body })
        .then(r => r.json())
        .then(() => { loadParts(); bootstrap.Modal.getInstance(document.getElementById('addPartModal')).hide(); })
        .catch(() => alert('Ошибка добавления запчасти'));
});
function removePart(id) {
    const body = new FormData();
    body.append('order_part_id', id);
    fetch('index.php?page=orders&action=removePart', { method: 'POST', body })
        .then(r => r.json())
        .then(() => loadParts())
        .catch(() => alert('Ошибка удаления запчасти'));
}
document.addEventListener('DOMContentLoaded', () => { loadParts(); loadPartOptions(); });
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
