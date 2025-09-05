<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0"><i class="fas fa-truck-loading text-primary"></i> Приход на склад</h1>
                <p class="text-muted mb-0">Оформление поступления товара</p>
            </div>
            <a href="index.php?page=inventory&action=movements" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Назад</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="index.php?page=inventory&action=purchase" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Позиция</label>
                    <select class="form-select" name="item_id" required>
                        <option value="">Выберите позицию</option>
                        <?php foreach ($items as $i): ?>
                            <option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['part_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Количество</label>
                    <input class="form-control" type="number" name="quantity" min="1" value="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Цена за ед.</label>
                    <div class="input-group">
                        <input class="form-control" type="number" name="unit_price" min="0" step="0.01" value="0">
                        <span class="input-group-text">₽</span>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>


