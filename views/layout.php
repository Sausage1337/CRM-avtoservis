<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --apple-blue: #007AFF;
            --apple-gray: #F2F2F7;
            --apple-gray-2: #E5E5EA;
            --apple-gray-3: #C7C7CC;
            --apple-gray-4: #D1D1D6;
            --apple-gray-5: #8E8E93;
            --apple-gray-6: #636366;
            --apple-black: #1C1C1E;
            --apple-white: #FFFFFF;
            --apple-red: #FF3B30;
            --apple-green: #34C759;
            --apple-orange: #FF9500;
            --apple-yellow: #FFCC00;
            --apple-purple: #AF52DE;
            --apple-pink: #FF2D92;
            --apple-teal: #5AC8FA;
            --apple-indigo: #5856D6;
            --apple-border-radius: 12px;
            --apple-border-radius-lg: 20px;
            --apple-shadow: 0 2px 16px rgba(0, 0, 0, 0.12);
            --apple-shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
        }

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--apple-gray);
            color: var(--apple-black);
            font-size: 15px;
            line-height: 1.4;
        }

        /* Боковая панель в стиле Apple */
        .sidebar {
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--apple-gray-2);
            padding: 20px 0;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }

        .sidebar.collapsed {
            width: 80px;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .text {
            opacity: 0;
            visibility: hidden;
            width: 0;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 8px;
            margin: 2px 8px;
            width: 64px;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-section {
            margin-bottom: 20px;
            padding: 0 8px;
        }

        .sidebar.collapsed .nav-link .icon {
            margin: 0;
            width: 20px;
            display: flex;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-link {
            gap: 0;
        }

        /* Tooltip для collapsed состояния */
        .sidebar.collapsed .nav-link {
            position: relative;
        }

        .sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--apple-black);
            color: white;
            padding: 8px 12px;
            border-radius: var(--apple-border-radius);
            font-size: 13px;
            white-space: nowrap;
            z-index: 1000;
            margin-left: 8px;
            opacity: 0;
            animation: tooltipFadeIn 0.2s ease forwards;
        }

        @keyframes tooltipFadeIn {
            to { opacity: 1; }
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            margin-bottom: 32px;
            font-weight: 600;
            font-size: 18px;
            color: var(--apple-black);
        }

        .sidebar-brand i {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--apple-blue), var(--apple-indigo));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .sidebar-section {
            margin-bottom: 40px;
        }

        .sidebar-title {
            padding: 0 24px 12px;
            font-size: 11px;
            font-weight: 600;
            color: var(--apple-gray-5);
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .sidebar .nav-link {
            color: var(--apple-black);
            padding: 12px 24px;
            margin: 2px 16px;
            border-radius: var(--apple-border-radius);
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            font-weight: 400;
            font-size: 15px;
            position: relative;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            background: rgba(0, 122, 255, 0.08);
            color: var(--apple-blue);
            transform: scale(1.02);
        }

        .sidebar .nav-link.active {
            background: var(--apple-blue);
            color: white;
            box-shadow: 0 4px 16px rgba(0, 122, 255, 0.25);
        }

        .sidebar .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .sidebar-link .icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar .sidebar-link .text {
            flex: 1;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            white-space: nowrap;
            overflow: hidden;
        }

        /* Основной контент */
        .main-content {
            background: transparent;
            min-height: 100vh;
            padding: 20px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .sidebar-collapsed .col-md-3.col-lg-2 {
            flex: 0 0 80px;
            max-width: 80px;
        }

        .sidebar-collapsed .col-md-9.col-lg-10 {
            flex: 0 0 calc(100% - 80px);
            max-width: calc(100% - 80px);
            margin-left: 0;
        }

        /* Apple Navigation Bar */
        .apple-navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--apple-gray-2);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 20px;
            color: var(--apple-black);
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: var(--apple-blue);
        }

        .navbar-brand i {
            background: linear-gradient(135deg, var(--apple-blue), var(--apple-indigo));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Apple Cards */
        .apple-card {
            background: white;
            border-radius: var(--apple-border-radius-lg);
            box-shadow: var(--apple-shadow);
            border: 1px solid var(--apple-gray-2);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            overflow: hidden;
        }

        .apple-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--apple-shadow-lg);
        }

        /* Apple Buttons */
        .btn-apple {
            background: var(--apple-blue);
            border: none;
            border-radius: var(--apple-border-radius);
            padding: 12px 24px;
            font-weight: 500;
            font-size: 15px;
            color: white;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .btn-apple:hover {
            background: #0056b3;
            transform: scale(1.02);
            color: white;
        }

        .btn-apple-secondary {
            background: var(--apple-gray);
            border: 1px solid var(--apple-gray-3);
            border-radius: var(--apple-border-radius);
            padding: 12px 24px;
            font-weight: 500;
            font-size: 15px;
            color: var(--apple-black);
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .btn-apple-secondary:hover {
            background: var(--apple-gray-4);
            transform: scale(1.02);
            color: var(--apple-black);
        }

        /* Apple Forms */
        .form-control-apple {
            border: 1px solid var(--apple-gray-3);
            border-radius: var(--apple-border-radius);
            padding: 16px;
            font-size: 15px;
            background: white;
            transition: all 0.2s ease;
        }

        .form-control-apple:focus {
            border-color: var(--apple-blue);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
            outline: none;
        }

        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: var(--apple-border-radius);
            background: rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .user-menu:hover {
            background: rgba(0, 0, 0, 0.08);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--apple-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--apple-black);
            line-height: 1.2;
        }

        .user-role {
            font-size: 12px;
            color: var(--apple-gray-5);
            line-height: 1.2;
        }

        /* Alerts в стиле Apple */
        .alert-apple {
            border: none;
            border-radius: var(--apple-border-radius);
            padding: 16px 20px;
            margin-bottom: 20px;
            font-size: 15px;
            position: relative;
        }

        .alert-apple.alert-success {
            background: rgba(52, 199, 89, 0.1);
            color: var(--apple-green);
            border-left: 4px solid var(--apple-green);
        }

        .alert-apple.alert-danger {
            background: rgba(255, 59, 48, 0.1);
            color: var(--apple-red);
            border-left: 4px solid var(--apple-red);
        }

        /* Responsive для мобильных устройств */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                width: 280px;
                height: 100vh;
                z-index: 1050;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                padding: 16px;
            }

            .apple-navbar {
                padding: 12px 0;
            }
        }

        /* Анимации */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease forwards;
        }
    </style>
</head>
<body>
    <?php
    // Проверяем и инициализируем необходимые ключи сессии
    if (isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
        $_SESSION['username'] = 'Пользователь';
    }
    if (isset($_SESSION['user_id']) && !isset($_SESSION['full_name'])) {
        $_SESSION['full_name'] = $_SESSION['username'] ?? 'Пользователь';
    }
    if (isset($_SESSION['user_id']) && !isset($_SESSION['role'])) {
        $_SESSION['role'] = 'user';
    }
    
    // Проверяем переменную $page
    if (!isset($page)) {
        $page = $_GET['page'] ?? 'dashboard';
    }
    ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Apple Navigation Bar -->
        <nav class="apple-navbar">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="index.php?page=dashboard">
                        <i class="fas fa-wrench"></i> <?= APP_NAME ?>
                    </a>
                    
                    <div class="d-flex align-items-center gap-3">
                        <!-- Sidebar toggle button -->
                        <button class="btn btn-apple-secondary d-none d-md-block" id="sidebar-collapse-toggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <div class="user-menu">
                            <div class="user-avatar">
                                <?php 
                                $r = $_SESSION['role'] ?? 'manager'; 
                                $icon = $r==='admin'?'fa-shield-alt':($r==='mechanic'?'fa-wrench':'fa-user-tie');
                                ?>
                                <i class="fas <?= $icon ?>"></i>
                            </div>
                            <div class="user-info">
                                <div class="user-name"><?= $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Пользователь' ?></div>
                                <div class="user-role"><?= ucfirst($r) ?></div>
                            </div>
                        </div>
                        
                        <a href="index.php?page=logout" class="btn btn-apple-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i>Выйти
                        </a>
                        
                        <!-- Mobile menu toggle -->
                        <button class="btn btn-apple-secondary d-md-none" id="sidebar-mobile-toggle">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <!-- Apple Sidebar -->
                <div class="col-md-3 col-lg-2 px-0">
                    <div class="sidebar" id="sidebar">
                        
                        <nav class="nav flex-column">
                            <div class="sidebar-section">
                                <div class="sidebar-title">Основное</div>
                                <a class="nav-link sidebar-link <?= $page === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard" data-title="Главная">
                                    <span class="icon"><i class="fas fa-chart-pie"></i></span>
                                    <span class="text">Главная</span>
                                </a>
                                <a class="nav-link sidebar-link <?= $page === 'clients' ? 'active' : '' ?>" href="index.php?page=clients" data-title="Клиенты">
                                    <span class="icon"><i class="fas fa-users"></i></span>
                                    <span class="text">Клиенты</span>
                                </a>
                                <a class="nav-link sidebar-link <?= $page === 'orders' ? 'active' : '' ?>" href="index.php?page=orders" data-title="Заказы">
                                    <span class="icon"><i class="fas fa-clipboard-list"></i></span>
                                    <span class="text">Заказы</span>
                                </a>
                                <a class="nav-link sidebar-link <?= $page === 'vehicles' ? 'active' : '' ?>" href="index.php?page=vehicles" data-title="Автомобили">
                                    <span class="icon"><i class="fas fa-car"></i></span>
                                    <span class="text">Автомобили</span>
                                </a>
                            </div>
                            
                            <div class="sidebar-section">
                                <div class="sidebar-title">Инструменты</div>
                                <a class="nav-link sidebar-link <?= $page === 'calculator' ? 'active' : '' ?>" href="index.php?page=calculator" data-title="Калькулятор">
                                    <span class="icon"><i class="fas fa-calculator"></i></span>
                                    <span class="text">Калькулятор</span>
                                </a>
                                <a class="nav-link sidebar-link <?= $page === 'inventory' ? 'active' : '' ?>" href="index.php?page=inventory&action=activity_log" data-title="Склад">
                                    <span class="icon"><i class="fas fa-boxes"></i></span>
                                    <span class="text">Склад</span>
                                </a>
                                <a class="nav-link sidebar-link <?= $page === 'process' ? 'active' : '' ?>" href="index.php?page=process" data-title="Процессы">
                                    <span class="icon"><i class="fas fa-cogs"></i></span>
                                    <span class="text">Процессы</span>
                                </a>
                                <a class="nav-link sidebar-link <?= $page === 'reports' ? 'active' : '' ?>" href="index.php?page=reports" data-title="Отчеты">
                                    <span class="icon"><i class="fas fa-chart-line"></i></span>
                                    <span class="text">Отчеты</span>
                                </a>
                                <a class="nav-link sidebar-link <?= $page === 'services' ? 'active' : '' ?>" href="index.php?page=services" data-title="Услуги">
                                    <span class="icon"><i class="fas fa-concierge-bell"></i></span>
                                    <span class="text">Услуги</span>
                                </a>
                                <a class="nav-link sidebar-link <?= $page === 'contact' ? 'active' : '' ?>" href="index.php?page=contact" data-title="Контакты">
                                    <span class="icon"><i class="fas fa-phone"></i></span>
                                    <span class="text">Контакты</span>
                                </a>
                                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                                <a class="nav-link sidebar-link <?= $page === 'users' ? 'active' : '' ?>" href="index.php?page=users" data-title="Пользователи">
                                    <span class="icon"><i class="fas fa-user-shield"></i></span>
                                    <span class="text">Пользователи</span>
                                </a>
                                <?php endif; ?>
                            </div>
                    </nav>
                    </div>
                </div>

                <!-- Основной контент -->
                <div class="col-md-9 col-lg-10">
                    <div class="main-content">
                        <?php if (!empty($_SESSION['success'])): ?>
                            <div class="alert-apple alert-success fade-in-up">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= htmlspecialchars($_SESSION['success']) ?>
                                <?php unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert-apple alert-danger fade-in-up">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= htmlspecialchars($_SESSION['error']) ?>
                                <?php unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Основной контент страницы -->
                        <div class="fade-in-up">
                        <?php include $content; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Контент для неавторизованных пользователей -->
        <?php include $content; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Apple-style smooth interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Desktop sidebar collapse toggle
            const sidebarCollapseToggle = document.getElementById('sidebar-collapse-toggle');
            const sidebar = document.getElementById('sidebar');
            const body = document.body;
            
            if (sidebarCollapseToggle && sidebar) {
                sidebarCollapseToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    body.classList.toggle('sidebar-collapsed');
                    
                    // Сохраняем состояние в localStorage
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                    
                    // Меняем иконку
                    const icon = this.querySelector('i');
                    if (isCollapsed) {
                        icon.className = 'fas fa-chevron-right';
                    } else {
                        icon.className = 'fas fa-bars';
                    }
                });
                
                // Восстанавливаем состояние из localStorage
                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState === 'true') {
                    sidebar.classList.add('collapsed');
                    body.classList.add('sidebar-collapsed');
                    const icon = sidebarCollapseToggle.querySelector('i');
                    icon.className = 'fas fa-chevron-right';
                }
            }
            
            // Mobile sidebar toggle
            const sidebarMobileToggle = document.getElementById('sidebar-mobile-toggle');
            
            if (sidebarMobileToggle && sidebar) {
                sidebarMobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar when clicking outside
                document.addEventListener('click', function(e) {
                    if (!sidebar.contains(e.target) && !sidebarMobileToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                });
            }
            
            // Add smooth hover effects
            document.querySelectorAll('.apple-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.alert-apple').forEach(alert => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 5000);
        });
    </script>

    <!-- WhiteByte Footer -->
    <footer class="whitebyte-footer">
        <div class="footer-content">
            <div class="footer-text">
                <span>Разработано</span>
                <strong>WhiteByte</strong>
            </div>
            <div class="footer-link">
                <a href="https://t.me/dazabey" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-telegram"></i>
                    <span>@dazabey</span>
                </a>
            </div>
        </div>
    </footer>

    <style>
    .whitebyte-footer {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border-top: 1px solid var(--apple-gray-2);
        padding: 20px 0;
        margin-top: 40px;
        font-size: 14px;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .footer-text {
        color: var(--apple-gray-6);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .footer-text strong {
        color: var(--apple-black);
        font-weight: 600;
    }

    .footer-link a {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--apple-blue);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 8px 12px;
        border-radius: var(--apple-border-radius);
    }

    .footer-link a:hover {
        background: rgba(0, 122, 255, 0.1);
        color: var(--apple-blue);
        transform: translateY(-1px);
    }

    .footer-link i {
        font-size: 16px;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            text-align: center;
            gap: 12px;
        }
        
        .whitebyte-footer {
            padding: 16px 0;
        }
    }
    </style>
</body>
</html>
