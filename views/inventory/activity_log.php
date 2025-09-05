<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0"><i class="fas fa-list text-primary"></i> Журнал склада</h1>
            <a href="index.php?page=inventory" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Назад</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clock"></i> Последние события</h6>
        </div>
        <div class="card-body">
            <?php if (empty($log)): ?>
                <div class="text-muted">Пока нет записей</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Действие</th>
                            <th>Сущность</th>
                            <th>Entity ID</th>
                            <th>Детали</th>
                            <th>Пользователь</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($log as $row): ?>
                        <tr>
                            <td><?= (int)$row['id'] ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($row['action']) ?></span></td>
                            <td><?= htmlspecialchars($row['entity']) ?></td>
                            <td><?= htmlspecialchars($row['entity_id']) ?></td>
                            <td><code><?= htmlspecialchars($row['details']) ?></code></td>
                            <td><?= htmlspecialchars($row['user_id'] ?? '-') ?></td>
                            <td><small class="text-muted"><?= htmlspecialchars($row['created_at']) ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


