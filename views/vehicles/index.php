<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-car"></i> Управление автомобилями
                    </h1>
                    <p class="text-muted">Список всех автомобилей в системе</p>
                </div>
                <a href="index.php?page=vehicles&action=create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Добавить автомобиль
                </a>
            </div>
        </div>
    </div>

    <!-- Поиск -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="index.php" class="row g-3">
                        <input type="hidden" name="page" value="vehicles">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Поиск</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?= htmlspecialchars($search) ?>" 
                                   placeholder="Госномер, марка, модель или клиент">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Найти
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <a href="index.php?page=vehicles" class="btn btn-secondary w-100">
                                <i class="fas fa-times"></i> Сброс
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Список автомобилей -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Список автомобилей (<?= count($vehicles) ?>)
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($vehicles)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Автомобили не найдены</h5>
                            <p class="text-muted">
                                <?php if (!empty($search)): ?>
                                    По вашему запросу ничего не найдено. Попробуйте изменить параметры поиска.
                                <?php else: ?>
                                    В системе пока нет автомобилей. Добавьте первый автомобиль.
                                <?php endif; ?>
                            </p>
                            <?php if (empty($search)): ?>
                                <a href="index.php?page=vehicles&action=create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Добавить автомобиль
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Госномер</th>
                                        <th>Марка/Модель</th>
                                        <th>VIN</th>
                                        <th>Год</th>
                                        <th>Клиент</th>
                                        <th>Дата добавления</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vehicles as $vehicle): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($vehicle['license_plate']) ?></strong>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($vehicle['brand']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($vehicle['model']) ?></small>
                                            </td>
                                            <td>
                                                <?php if ($vehicle['vin']): ?>
                                                    <code><?= htmlspecialchars($vehicle['vin']) ?></code>
                                                <?php else: ?>
                                                    <span class="text-muted">Не указан</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= $vehicle['year'] ?: '<span class="text-muted">Не указан</span>' ?>
                                            </td>
                                            <td>
                                                <?php if ($vehicle['client_name']): ?>
                                                    <?= htmlspecialchars($vehicle['client_name']) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Не привязан</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d.m.Y H:i', strtotime($vehicle['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?page=vehicles&action=edit&id=<?= $vehicle['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Редактировать">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="index.php?page=vehicles&action=view&id=<?= $vehicle['id'] ?>" 
                                                       class="btn btn-sm btn-outline-info" title="Просмотр">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDelete(<?= $vehicle['id'] ?>)" title="Удалить">
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

<script>
function confirmDelete(vehicleId) {
    if (confirm('Вы уверены, что хотите удалить этот автомобиль? Это действие нельзя отменить.')) {
        window.location.href = 'index.php?page=vehicles&action=delete&id=' + vehicleId;
    }
}
</script>
