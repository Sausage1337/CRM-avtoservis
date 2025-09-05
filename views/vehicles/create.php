<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-plus"></i> Добавить автомобиль
                    </h1>
                    <p class="text-muted">Заполните информацию об автомобиле</p>
                </div>
                <a href="index.php?page=vehicles" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Назад к списку
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-car"></i> Информация об автомобиле
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?page=vehicles&action=create" id="vehicleForm">
                        <div class="row">
                            <!-- Госномер -->
                            <div class="col-md-6 mb-3">
                                <label for="license_plate" class="form-label">
                                    <i class="fas fa-hashtag"></i> Госномер *
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="license_plate" name="license_plate" 
                                           value="<?= htmlspecialchars($formData['license_plate'] ?? '') ?>" 
                                           placeholder="А123БВ77" required>
                                    <button type="button" class="btn btn-outline-primary" onclick="getVehicleInfo()">
                                        <i class="fas fa-search"></i> Найти
                                    </button>
                                </div>
                                <small class="form-text text-muted">
                                    Введите госномер для автоматического получения данных
                                </small>
                            </div>

                            <!-- VIN -->
                            <div class="col-md-6 mb-3">
                                <label for="vin" class="form-label">
                                    <i class="fas fa-barcode"></i> VIN
                                </label>
                                <input type="text" class="form-control" id="vin" name="vin" 
                                       value="<?= htmlspecialchars($formData['vin'] ?? '') ?>" 
                                       placeholder="17-значный VIN код">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Марка -->
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">
                                    <i class="fas fa-tag"></i> Марка *
                                </label>
                                <input type="text" class="form-control" id="brand" name="brand" 
                                       value="<?= htmlspecialchars($formData['brand'] ?? '') ?>" 
                                       placeholder="Toyota" required>
                            </div>

                            <!-- Модель -->
                            <div class="col-md-6 mb-3">
                                <label for="model" class="form-label">
                                    <i class="fas fa-car"></i> Модель *
                                </label>
                                <input type="text" class="form-control" id="model" name="model" 
                                       value="<?= htmlspecialchars($formData['model'] ?? '') ?>" 
                                       placeholder="Camry" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Год выпуска -->
                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label">
                                    <i class="fas fa-calendar"></i> Год выпуска
                                </label>
                                <select class="form-control" id="year" name="year">
                                    <option value="">Выберите год</option>
                                    <?php for ($y = date('Y'); $y >= 1990; $y--): ?>
                                        <option value="<?= $y ?>" <?= ($formData['year'] ?? '') == $y ? 'selected' : '' ?>>
                                            <?= $y ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Объем двигателя -->
                            <div class="col-md-4 mb-3">
                                <label for="engine_volume" class="form-label">
                                    <i class="fas fa-cog"></i> Объем двигателя (л)
                                </label>
                                <input type="number" class="form-control" id="engine_volume" name="engine_volume" 
                                       value="<?= htmlspecialchars($formData['engine_volume'] ?? '') ?>" 
                                       step="0.1" min="0.5" max="10" placeholder="2.5">
                            </div>

                            <!-- Тип топлива -->
                            <div class="col-md-4 mb-3">
                                <label for="fuel_type" class="form-label">
                                    <i class="fas fa-gas-pump"></i> Тип топлива
                                </label>
                                <select class="form-control" id="fuel_type" name="fuel_type">
                                    <option value="">Выберите тип</option>
                                    <option value="petrol" <?= ($formData['fuel_type'] ?? '') == 'petrol' ? 'selected' : '' ?>>Бензин</option>
                                    <option value="diesel" <?= ($formData['fuel_type'] ?? '') == 'diesel' ? 'selected' : '' ?>>Дизель</option>
                                    <option value="hybrid" <?= ($formData['fuel_type'] ?? '') == 'hybrid' ? 'selected' : '' ?>>Гибрид</option>
                                    <option value="electric" <?= ($formData['fuel_type'] ?? '') == 'electric' ? 'selected' : '' ?>>Электро</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Коробка передач -->
                            <div class="col-md-6 mb-3">
                                <label for="transmission" class="form-label">
                                    <i class="fas fa-cogs"></i> Коробка передач
                                </label>
                                <select class="form-control" id="transmission" name="transmission">
                                    <option value="">Выберите тип</option>
                                    <option value="manual" <?= ($formData['transmission'] ?? '') == 'manual' ? 'selected' : '' ?>>Механика</option>
                                    <option value="automatic" <?= ($formData['transmission'] ?? '') == 'automatic' ? 'selected' : '' ?>>Автомат</option>
                                </select>
                            </div>

                            <!-- Цвет -->
                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">
                                    <i class="fas fa-palette"></i> Цвет
                                </label>
                                <input type="text" class="form-control" id="color" name="color" 
                                       value="<?= htmlspecialchars($formData['color'] ?? '') ?>" 
                                       placeholder="Белый">
                            </div>
                        </div>

                        <!-- Клиент -->
                        <div class="mb-4">
                            <label for="client_id" class="form-label">
                                <i class="fas fa-user"></i> Клиент
                            </label>
                            <select class="form-control" id="client_id" name="client_id">
                                <option value="">Выберите клиента</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id'] ?>" 
                                            <?= ($formData['client_id'] ?? '') == $client['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($client['full_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">
                                Если клиент не выбран, автомобиль можно будет привязать позже
                            </small>
                        </div>

                        <!-- Кнопки -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Сохранить автомобиль
                            </button>
                            <a href="index.php?page=vehicles" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Отмена
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getVehicleInfo() {
    const licensePlate = document.getElementById('license_plate').value.trim();
    
    if (!licensePlate) {
        alert('Введите госномер для поиска информации');
        return;
    }
    
    // Показываем индикатор загрузки
    const searchBtn = event.target;
    const originalText = searchBtn.innerHTML;
    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Поиск...';
    searchBtn.disabled = true;
    
    // Запрос к API
    fetch(`index.php?page=vehicles&action=getVehicleInfo&license_plate=${encodeURIComponent(licensePlate)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Заполняем поля автоматически
                document.getElementById('brand').value = data.data.brand || '';
                document.getElementById('model').value = data.data.model || '';
                document.getElementById('year').value = data.data.year || '';
                document.getElementById('vin').value = data.data.vin || '';
                document.getElementById('engine_volume').value = data.data.engine_volume || '';
                document.getElementById('fuel_type').value = data.data.fuel_type || '';
                document.getElementById('transmission').value = data.data.transmission || '';
                document.getElementById('color').value = data.data.color || '';
                
                alert('Информация об автомобиле найдена и заполнена автоматически!');
            } else {
                alert('Информация об автомобиле не найдена. Заполните поля вручную.');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Ошибка при получении информации об автомобиле. Заполните поля вручную.');
        })
        .finally(() => {
            // Восстанавливаем кнопку
            searchBtn.innerHTML = originalText;
            searchBtn.disabled = false;
        });
}
</script>
