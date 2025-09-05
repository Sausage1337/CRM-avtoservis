<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0"><i class="fas fa-plus text-primary"></i> Новая позиция</h1>
                <p class="text-muted mb-0">Создание позиции склада</p>
            </div>
            <a href="index.php?page=inventory" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Назад</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="index.php?page=inventory&action=create" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Наименование</label>
                    <input class="form-control" type="text" name="part_name" required placeholder="Например, Масляный фильтр">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Артикул</label>
                    <input class="form-control" type="text" name="part_number" placeholder="Например, ABC-123">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Единица</label>
                    <input class="form-control" type="text" name="unit" value="шт">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Цена</label>
                    <div class="input-group">
                        <input class="form-control" type="number" name="price" min="0" step="0.01" value="0">
                        <span class="input-group-text">₽</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Мин. остаток</label>
                    <input class="form-control" type="number" name="min_stock" min="0" value="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Начальный остаток</label>
                    <input class="form-control" type="number" name="initial_stock" min="0" value="0">
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>


