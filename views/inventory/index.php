<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0"><i class="fas fa-warehouse text-primary"></i> Склад</h1>
                <p class="text-muted mb-0">Остатки и минимальные уровни</p>
            </div>
            <div>
                <a href="index.php?page=inventory&action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Новая позиция</a>
                <a href="index.php?page=inventory&action=purchase" class="btn btn-outline-primary"><i class="fas fa-cart-plus"></i> Приход</a>
                <a href="index.php?page=inventory&action=movements" class="btn btn-outline-secondary"><i class="fas fa-exchange-alt"></i> Движения</a>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-boxes"></i> Позиции склада</h6>
            <span class="badge bg-primary"><?= count($items) ?> позиций</span>
        </div>
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET" action="index.php">
                <input type="hidden" name="page" value="inventory">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Поиск (название, артикул, штрихкод)">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="supplier_id">
                        <option value="">Поставщик: любой</option>
                        <?php foreach (($suppliers ?? []) as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= ($supplierId ?? '') == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 form-check d-flex align-items-center ps-4">
                    <input class="form-check-input me-2" type="checkbox" name="low_only" value="1" <?= !empty($lowOnly) ? 'checked' : '' ?>>
                    <label class="form-check-label">Только ниже минимума</label>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100" type="submit"><i class="fas fa-search"></i> Найти</button>
                </div>
            </form>
            <?php if (empty($items)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-box-open fa-2x mb-2"></i>
                    <div>Нет позиций склада</div>
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Наименование</th>
                            <th>Артикул</th>
                            <th>Штрихкод</th>
                            <th>Поставщик</th>
                            <th>Ед.</th>
                            <th>Коэфф.</th>
                            <th>Цена</th>
                            <th>Мин. остаток</th>
                            <th>В наличии</th>
                            <th>Доступно</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['part_name']) ?></td>
                            <td><?= htmlspecialchars($item['part_number']) ?></td>
                            <td><?= htmlspecialchars($item['barcode'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['supplier_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['unit']) ?></td>
                            <td><?= htmlspecialchars($item['unit_factor'] ?? 1) ?></td>
                            <td><?= number_format($item['price'], 0, ',', ' ') ?> ₽</td>
                            <td><span class="badge bg-secondary"><?= (int)$item['min_stock'] ?></span></td>
                            <td><span class="badge <?= (int)($item['current_stock'] ?? $item['stock']) < (int)$item['min_stock'] ? 'bg-danger' : 'bg-success' ?>"><?= (int)($item['current_stock'] ?? $item['stock']) ?></span></td>
                            <td><span class="badge bg-info"><?= (int)($item['available_stock'] ?? max(0, (int)$item['stock'] - (int)$item['reserved'])) ?></span></td>
                            <td>
                                <a href="index.php?page=inventory&action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($lowStock)): ?>
    <div class="card shadow mt-4 border-danger">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-triangle-exclamation"></i> Позиции ниже минимума
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Наименование</th>
                            <th>Артикул</th>
                            <th>Мин.</th>
                            <th>Остаток</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStock as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['part_name']) ?></td>
                            <td><?= htmlspecialchars($row['part_number']) ?></td>
                            <td><?= (int)$row['min_stock'] ?></td>
                            <td><span class="badge bg-danger"><?= (int)$row['current_stock'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>


