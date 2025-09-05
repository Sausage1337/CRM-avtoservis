<div class="container-fluid">
    <!-- Заголовок страницы -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit text-primary"></i> Редактирование автомобиля
                    </h1>
                    <p class="text-muted mb-0">ID: <?= (int)$vehicle['id'] ?> • Госномер: <?= htmlspecialchars($vehicle['license_plate']) ?></p>
                </div>
                <a href="index.php?page=vehicles" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Назад к списку
                </a>
            </div>
        </div>
    </div>

    <!-- Форма редактирования -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-car"></i> Данные автомобиля
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?page=vehicles&action=edit&id=<?= (int)$vehicle['id'] ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-credit-card text-primary"></i> Госномер</label>
                                <input type="text" class="form-control" name="license_plate" required value="<?= htmlspecialchars(($formData['license_plate'] ?? $vehicle['license_plate']) ) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-barcode text-primary"></i> VIN</label>
                                <input type="text" class="form-control" name="vin" value="<?= htmlspecialchars(($formData['vin'] ?? $vehicle['vin']) ) ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-industry text-primary"></i> Марка</label>
                                <input type="text" class="form-control" name="brand" required value="<?= htmlspecialchars(($formData['brand'] ?? $vehicle['brand']) ) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-car-side text-primary"></i> Модель</label>
                                <input type="text" class="form-control" name="model" required value="<?= htmlspecialchars(($formData['model'] ?? $vehicle['model']) ) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-calendar text-primary"></i> Год</label>
                                <input type="number" class="form-control" name="year" min="1900" max="2100" value="<?= htmlspecialchars(($formData['year'] ?? $vehicle['year']) ) ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-gas-pump text-primary"></i> Двигатель (л)</label>
                                <input type="text" class="form-control" name="engine_volume" value="<?= htmlspecialchars(($formData['engine_volume'] ?? $vehicle['engine_volume']) ) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-burn text-primary"></i> Топливо</label>
                                <select class="form-select" name="fuel_type">
                                    <?php $fuel = ($formData['fuel_type'] ?? $vehicle['fuel_type']); ?>
                                    <option value="">—</option>
                                    <option value="petrol" <?= $fuel==='petrol'?'selected':'' ?>>Бензин</option>
                                    <option value="diesel" <?= $fuel==='diesel'?'selected':'' ?>>Дизель</option>
                                    <option value="hybrid" <?= $fuel==='hybrid'?'selected':'' ?>>Гибрид</option>
                                    <option value="electric" <?= $fuel==='electric'?'selected':'' ?>>Электро</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><i class="fas fa-cogs text-primary"></i> Трансмиссия</label>
                                <select class="form-select" name="transmission">
                                    <?php $tr = ($formData['transmission'] ?? $vehicle['transmission']); ?>
                                    <option value="">—</option>
                                    <option value="manual" <?= $tr==='manual'?'selected':'' ?>>Механика</option>
                                    <option value="automatic" <?= $tr==='automatic'?'selected':'' ?>>Автомат</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-palette text-primary"></i> Цвет</label>
                                <input type="text" class="form-control" name="color" value="<?= htmlspecialchars(($formData['color'] ?? $vehicle['color']) ) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fas fa-user text-primary"></i> Клиент</label>
                                <select class="form-select" name="client_id">
                                    <option value="">—</option>
                                    <?php foreach ($clients as $c): ?>
                                        <option value="<?= (int)$c['id'] ?>" <?= ((int)($formData['client_id'] ?? $vehicle['client_id']))===(int)$c['id']?'selected':'' ?>>
                                            <?= htmlspecialchars($c['full_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="index.php?page=vehicles" class="btn btn-secondary"><i class="fas fa-times"></i> Отмена</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


