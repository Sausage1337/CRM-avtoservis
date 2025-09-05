<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0"><i class="fas fa-truck text-primary"></i> Поставщики</h1>
                <p class="text-muted mb-0">Справочник поставщиков</p>
            </div>
            <a href="index.php?page=inventory" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Назад</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <?php if (empty($suppliers)): ?>
                <div class="text-center text-muted py-4">Поставщики не добавлены</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Наименование</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Адрес</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['name']) ?></td>
                            <td><?= htmlspecialchars($s['phone']) ?></td>
                            <td><?= htmlspecialchars($s['email']) ?></td>
                            <td><?= htmlspecialchars($s['address']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


