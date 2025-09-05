<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0"><i class="fas fa-user-shield text-primary"></i> Роли пользователей</h1>
            <a href="index.php?page=dashboard" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Назад</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Логин</th>
                            <th>Имя</th>
                            <th>Роль</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= (int)$u['id'] ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['full_name']) ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?= htmlspecialchars($u['role']) ?>
                                </span>
                            </td>
                            <td>
                                <form class="d-flex" method="POST" action="index.php?page=users&action=updateRole">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                    <select class="form-select form-select-sm me-2" name="role">
                                        <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>admin</option>
                                        <option value="manager" <?= $u['role']==='manager'?'selected':'' ?>>manager</option>
                                        <option value="mechanic" <?= $u['role']==='mechanic'?'selected':'' ?>>mechanic</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary" type="submit"><i class="fas fa-save"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


