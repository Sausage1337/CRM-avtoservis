<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0"><i class="fas fa-edit text-primary"></i> Редактирование позиции</h1>
                <p class="text-muted mb-0">Изменение параметров и корректировка остатка</p>
            </div>
            <a href="index.php?page=inventory" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Назад</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="index.php?page=inventory&action=edit&id=<?= $item['id'] ?>" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Наименование</label>
                    <input class="form-control" type="text" name="part_name" required value="<?= htmlspecialchars($item['part_name']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Артикул</label>
                    <input class="form-control" type="text" name="part_number" value="<?= htmlspecialchars($item['part_number']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Единица</label>
                    <input class="form-control" type="text" name="unit" value="<?= htmlspecialchars($item['unit']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Цена</label>
                    <div class="input-group">
                        <input class="form-control" type="number" name="price" min="0" step="0.01" value="<?= htmlspecialchars($item['price']) ?>">
                        <span class="input-group-text">₽</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Мин. остаток</label>
                    <input class="form-control" type="number" name="min_stock" min="0" value="<?= (int)$item['min_stock'] ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Корректировка остатка</label>
                    <input class="form-control" type="number" name="adjust_stock" placeholder="Напр., +5 или -3">
                    <div class="form-text">Положительное значение — увеличить, отрицательное — уменьшить остаток</div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>


