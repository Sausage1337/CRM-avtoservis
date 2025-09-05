<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0"><i class="fas fa-exchange-alt text-primary"></i> Движения склада</h1>
                <p class="text-muted mb-0">История приходов и расходов</p>
            </div>
            <a href="index.php?page=inventory&action=purchase" class="btn btn-primary"><i class="fas fa-plus"></i> Приход</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <form class="row g-2" method="GET" action="index.php">
                <input type="hidden" name="page" value="inventory">
                <input type="hidden" name="action" value="movements">
                <div class="col-md-6">
                    <select class="form-select" name="item_id">
                        <option value="">Все позиции</option>
                        <?php foreach ($items as $i): ?>
                            <option value="<?= $i['id'] ?>" <?= $selectedItemId == $i['id'] ? 'selected' : '' ?>><?= htmlspecialchars($i['part_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-filter"></i> Фильтр</button>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-outline-secondary" href="index.php?page=inventory&action=movements"><i class="fas fa-times"></i> Сброс</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <?php if (empty($movements)): ?>
                <div class="text-center text-muted py-4">Нет данных</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Дата</th>
                            <th>Позиция</th>
                            <th>Тип</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th>Связанный заказ</th>
                            <th>Комментарий</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movements as $m): ?>
                        <tr>
                            <td><?= date('d.m.Y H:i', strtotime($m['created_at'])) ?></td>
                            <td><?= htmlspecialchars($m['part_name']) ?></td>
                            <td>
                                <?php
                                $cls = $m['type'] === 'in' ? 'bg-success' : ($m['type'] === 'out' ? 'bg-danger' : 'bg-secondary');
                                $txt = $m['type'] === 'in' ? 'Приход' : ($m['type'] === 'out' ? 'Расход' : 'Корректировка');
                                ?>
                                <span class="badge <?= $cls ?>"><?= $txt ?></span>
                            </td>
                            <td><?= (int)$m['quantity'] ?></td>
                            <td><?= number_format($m['unit_price'], 0, ',', ' ') ?> ₽</td>
                            <td><?= $m['related_order_id'] ? ('#' . (int)$m['related_order_id']) : '-' ?></td>
                            <td><?= htmlspecialchars($m['note'] ?? '') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


