<!-- Apple-style Services Management -->
<div class="row mb-4">
    <div class="col-12">
        <div class="apple-card p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2 fw-semibold">Управление услугами</h1>
                    <p class="text-secondary mb-0">Редактирование списка услуг автосервиса</p>
                </div>
                <div class="d-flex gap-3">
                    <button class="btn btn-apple-secondary" id="categoryFilter">
                        <i class="fas fa-filter me-2"></i>Фильтр
                    </button>
                    <a href="index.php?page=services&action=create" class="btn btn-apple">
                        <i class="fas fa-plus me-2"></i>Добавить услугу
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="apple-card p-3">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-0">
                    <i class="fas fa-search text-secondary"></i>
                </span>
                <input type="text" class="form-control-apple border-0" id="serviceSearch" 
                       placeholder="Поиск услуг..." style="background: transparent;">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="apple-card p-3">
            <select class="form-control-apple border-0" id="categorySelect" style="background: transparent;">
                <option value="">Все категории</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['category']) ?>">
                        <?= htmlspecialchars($cat['category']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<!-- Services Grid -->
<div class="row" id="servicesGrid">
    <?php 
    $currentCategory = '';
    foreach ($services as $service): 
        if ($service['category'] !== $currentCategory): 
            if ($currentCategory !== ''): ?>
                </div></div>
            <?php endif; 
            $currentCategory = $service['category']; ?>
            <div class="col-12 mb-4">
                <div class="category-section">
                    <h5 class="fw-semibold text-secondary mb-3">
                        <i class="fas fa-tools me-2"></i>
                        <?= $currentCategory ?: 'Без категории' ?>
                    </h5>
                    <div class="row">
        <?php endif; ?>
        
        <div class="col-lg-4 col-md-6 mb-3" data-service-name="<?= strtolower($service['name']) ?>" 
             data-category="<?= htmlspecialchars($service['category']) ?>">
            <div class="apple-card service-card h-100">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-2"><?= htmlspecialchars($service['name']) ?></h6>
                            <?php if ($service['description']): ?>
                                <p class="text-secondary small mb-2" style="font-size: 13px;">
                                    <?= htmlspecialchars($service['description']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-secondary p-1" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="index.php?page=services&action=edit&id=<?= $service['id'] ?>">
                                        <i class="fas fa-edit me-2"></i>Редактировать
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?page=services&action=toggle&id=<?= $service['id'] ?>">
                                        <i class="fas fa-<?= $service['active'] ? 'eye-slash' : 'eye' ?> me-2"></i>
                                        <?= $service['active'] ? 'Деактивировать' : 'Активировать' ?>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete(<?= $service['id'] ?>)">
                                        <i class="fas fa-trash me-2"></i>Удалить
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="service-price">
                            <span class="h5 mb-0 text-success fw-semibold">
                                <?= number_format($service['price'], 0, ',', ' ') ?> ₽
                            </span>
                        </div>
                        <div class="service-meta text-end">
                            <small class="text-secondary d-block">
                                <i class="fas fa-clock me-1"></i><?= $service['duration'] ?> мин
                            </small>
                            <small class="<?= $service['active'] ? 'text-success' : 'text-danger' ?>">
                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                <?= $service['active'] ? 'Активна' : 'Неактивна' ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if ($currentCategory !== ''): ?>
        </div></div>
    <?php endif; ?>
</div>

<?php if (empty($services)): ?>
<div class="row">
    <div class="col-12">
        <div class="apple-card p-5 text-center">
            <div class="text-secondary mb-4">
                <i class="fas fa-tools fa-4x opacity-50"></i>
            </div>
            <h5 class="text-secondary mb-3">Услуги не найдены</h5>
            <p class="text-secondary mb-4">Создайте первую услугу для автосервиса</p>
            <a href="index.php?page=services&action=create" class="btn btn-apple">
                <i class="fas fa-plus me-2"></i>Создать услугу
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.service-card {
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    cursor: pointer;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--apple-shadow-lg);
}

.category-section {
    margin-bottom: 2rem;
}

.dropdown-menu {
    border: 1px solid var(--apple-gray-2);
    border-radius: var(--apple-border-radius);
    box-shadow: var(--apple-shadow);
    padding: 8px 0;
}

.dropdown-item {
    padding: 8px 16px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: var(--apple-gray);
}

.service-meta small {
    line-height: 1.3;
}

@media (max-width: 768px) {
    .service-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('serviceSearch');
    const categorySelect = document.getElementById('categorySelect');
    
    function filterServices() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categorySelect.value;
        const serviceCards = document.querySelectorAll('[data-service-name]');
        
        serviceCards.forEach(card => {
            const serviceName = card.getAttribute('data-service-name');
            const serviceCategory = card.getAttribute('data-category');
            
            const matchesSearch = serviceName.includes(searchTerm);
            const matchesCategory = !selectedCategory || serviceCategory === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
                card.classList.add('fade-in-up');
            } else {
                card.style.display = 'none';
            }
        });
        
        // Скрываем/показываем заголовки категорий
        document.querySelectorAll('.category-section').forEach(section => {
            const visibleCards = section.querySelectorAll('[data-service-name]:not([style*="display: none"])');
            section.style.display = visibleCards.length > 0 ? 'block' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterServices);
    categorySelect.addEventListener('change', filterServices);
});

function confirmDelete(serviceId) {
    if (confirm('Вы уверены, что хотите удалить эту услугу?\n\nЕсли услуга используется в заказах, она будет деактивирована.')) {
        window.location.href = `index.php?page=services&action=delete&id=${serviceId}`;
    }
}
</script>
