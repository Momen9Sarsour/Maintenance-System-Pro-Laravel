<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Maintenance Management')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ===== متغيرات الألوان ===== */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --sidebar-width: 260px;
            --header-height: 60px;
            --sidebar-gradient-start: #667eea;
            --sidebar-gradient-end: #764ba2;
            --transition-speed: 0.3s;
        }

        /* ===== Light Mode (default) ===== */
        [data-bs-theme="light"] {
            --bg-body: #f0f2f5;
            --bg-card: #ffffff;
            --bg-navbar: #ffffff;
            --bg-sidebar: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            --text-primary: #2c3e50;
            --footer-sidebar: #966ebd;
            --text-secondary: #6c757d;
            --text-muted: #8a9ba8;
            --border-color: #e9ecef;
            --shadow-color: rgba(0,0,0,0.06);
            --hover-bg: rgba(102, 126, 234, 0.05);
            --input-bg: #ffffff;
            --table-stripe: #f8f9fa;
        }

        /* ===== Dark Mode ===== */
        [data-bs-theme="dark"] {
            --bg-body: #0d1117;
            --bg-card: #161b22;
            --bg-navbar: #161b22;
            --footer-sidebar: #323d49;
            --bg-sidebar: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            --text-primary: #e6edf3;
            --text-secondary: #8b949e;
            --text-muted: #484f58;
            --border-color: #30363d;
            --shadow-color: rgba(0,0,0,0.3);
            --hover-bg: rgba(255,255,255,0.05);
            --input-bg: #0d1117;
            --table-stripe: #0d1117;
        }

        /* ===== Base ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-body); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--text-muted); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-secondary); }

        /* ===== Sidebar ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: all var(--transition-speed) ease;
            overflow-y: auto;
            box-shadow: 2px 0 10px var(--shadow-color);
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 10px; }

        .sidebar-brand {
            padding: 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand i { font-size: 28px; }
        .sidebar-brand h4 { font-weight: 700; margin: 0; font-size: 20px; letter-spacing: 0.5px; }
        .sidebar-brand small { font-weight: 300; opacity: 0.7; font-size: 12px; }

        .sidebar-menu { padding: 15px 0; }

        .sidebar-menu .menu-label {
            padding: 10px 20px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: 0.5;
            font-weight: 700;
            color: rgba(255,255,255,0.7);
        }

        .sidebar-menu .nav-item { padding: 0 12px; margin-bottom: 2px; }

        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 10px 16px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            font-weight: 500;
            transition: all var(--transition-speed) ease;
            text-decoration: none;
            position: relative;
        }

        .sidebar-menu .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-menu .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .sidebar-menu .nav-link i { width: 20px; font-size: 16px; }

        .sidebar-menu .nav-link .badge {
            margin-left: auto;
            background: rgba(255,255,255,0.2);
            color: white;
            font-size: 10px;
            padding: 2px 10px;
            border-radius: 20px;
        }

        .sidebar-menu .nav-link .badge.danger { background: var(--danger-color); }
        .sidebar-menu .nav-link .badge.success { background: var(--success-color); }

        .sidebar-footer {
            position:sticky;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background-color: var(--footer-sidebar);
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-footer .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-footer .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .sidebar-footer .user-name { font-weight: 600; font-size: 14px; }
        .sidebar-footer .user-role { font-size: 11px; opacity: 0.7; }

        /* ===== Main Content ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all var(--transition-speed) ease;
        }

        /* ===== Top Navbar ===== */
        .top-navbar {
            background: var(--bg-navbar);
            padding: 12px 30px;
            box-shadow: 0 2px 10px var(--shadow-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            transition: all var(--transition-speed) ease;
            border-bottom: 1px solid var(--border-color);
        }

        .top-navbar .page-title {
            font-weight: 600;
            font-size: 20px;
            color: var(--text-primary);
            transition: color var(--transition-speed);
        }

        .top-navbar .page-title small {
            font-weight: 400;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .top-navbar .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-navbar .nav-actions .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-speed) ease;
            position: relative;
            font-size: 18px;
            cursor: pointer;
        }

        .top-navbar .nav-actions .btn-icon:hover {
            background: var(--sidebar-gradient-start);
            color: white;
            border-color: var(--sidebar-gradient-start);
        }

        .top-navbar .nav-actions .btn-icon .badge-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--danger-color);
            border-radius: 50%;
            border: 2px solid var(--bg-navbar);
        }

        .top-navbar .nav-actions .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px 12px 5px 5px;
            border-radius: 50px;
            background: var(--bg-body);
            border: 1px solid var(--border-color);
            transition: all var(--transition-speed);
        }

        .top-navbar .nav-actions .user-profile:hover {
            border-color: var(--sidebar-gradient-start);
        }

        .top-navbar .nav-actions .user-profile .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--sidebar-gradient-start);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        /* ===== Theme Toggle ===== */
        .theme-toggle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .theme-toggle:hover {
            background: var(--sidebar-gradient-start);
            color: white;
            border-color: var(--sidebar-gradient-start);
        }

        /* ===== Cards ===== */
        .stat-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 2px 12px var(--shadow-color);
            transition: all var(--transition-speed) ease;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px var(--shadow-color);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-card .stat-icon.primary { background: rgba(102, 126, 234, 0.12); color: #667eea; }
        .stat-card .stat-icon.success { background: rgba(39, 174, 96, 0.12); color: #27ae60; }
        .stat-card .stat-icon.warning { background: rgba(243, 156, 18, 0.12); color: #f39c12; }
        .stat-card .stat-icon.danger { background: rgba(231, 76, 60, 0.12); color: #e74c3c; }
        .stat-card .stat-icon.info { background: rgba(52, 152, 219, 0.12); color: #3498db; }
        .stat-card .stat-icon.purple { background: rgba(118, 75, 162, 0.12); color: #764ba2; }

        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 8px 0 4px;
            transition: color var(--transition-speed);
        }

        .stat-card .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .stat-change {
            font-size: 12px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
        }

        .stat-card .stat-change.up { background: rgba(39, 174, 96, 0.12); color: #27ae60; }
        .stat-card .stat-change.down { background: rgba(231, 76, 60, 0.12); color: #e74c3c; }

        /* ===== Card Custom ===== */
        .card-custom {
            background: var(--bg-card);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 12px var(--shadow-color);
            overflow: hidden;
            transition: all var(--transition-speed);
        }

        .card-custom .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 18px 24px;
            font-weight: 600;
            font-size: 15px;
            color: var(--text-primary);
        }

        .card-custom .card-body {
            padding: 20px 24px;
        }

        /* ===== Tables ===== */
        .table-custom {
            font-size: 13px;
            color: var(--text-primary);
        }

        .table-custom thead th {
            background: var(--bg-body);
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid var(--border-color);
        }

        .table-custom tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .table-custom tbody tr:hover {
            background: var(--hover-bg);
        }

        .table-custom tbody tr:nth-child(even) {
            background: var(--table-stripe);
        }

        /* ===== Badges ===== */
        .badge-status {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-status.pending { background: #fff3cd; color: #856404; }
        .badge-status.in-progress { background: #cce5ff; color: #004085; }
        .badge-status.in_progress { background: #cce5ff; color: #004085; }
        .badge-status.completed { background: #d4edda; color: #155724; }
        .badge-status.cancelled { background: #f8d7da; color: #721c24; }
        .badge-status.overdue { background: #f8d7da; color: #721c24; }
        .badge-status.on-time { background: #d4edda; color: #155724; }
        .badge-status.paid { background: #d4edda; color: #155724; }
        .badge-status.unpaid { background: #fff3cd; color: #856404; }
        .badge-status.draft { background: #e9ecef; color: #6c757d; }
        .badge-status.sent { background: #cce5ff; color: #004085; }

        .badge-priority {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-priority.low { background: #e9ecef; color: #6c757d; }
        .badge-priority.medium { background: #cce5ff; color: #004085; }
        .badge-priority.high { background: #fff3cd; color: #856404; }
        .badge-priority.critical { background: #f8d7da; color: #721c24; }

        /* ===== Progress ===== */
        .progress-custom {
            height: 6px;
            border-radius: 10px;
            background: var(--bg-body);
            overflow: hidden;
        }

        .progress-custom .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* ===== Responsive ===== */
        .sidebar-toggle {
            display: none;
            background: transparent;
            border: none;
            font-size: 24px;
            color: var(--text-primary);
            padding: 5px;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: block; }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.4);
                z-index: 999;
            }

            .sidebar-overlay.show { display: block; }
        }

        @media (max-width: 576px) {
            .top-navbar {
                padding: 10px 16px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .top-navbar .page-title { font-size: 16px; }
            .top-navbar .page-title small { font-size: 12px; display: block; }

            .stat-card .stat-number { font-size: 22px; }
            .stat-card { padding: 16px; }

            .card-custom .card-header { padding: 14px 16px; font-size: 14px; }
            .card-custom .card-body { padding: 14px 16px; }

            .top-navbar .nav-actions .btn-icon { width: 35px; height: 35px; font-size: 15px; }
            .top-navbar .nav-actions .user-profile .avatar { width: 30px; height: 30px; font-size: 12px; }
        }

        /* ===== Animations ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        .animate-fade-in:nth-child(2) { animation-delay: 0.05s; }
        .animate-fade-in:nth-child(3) { animation-delay: 0.1s; }
        .animate-fade-in:nth-child(4) { animation-delay: 0.15s; }
        .animate-fade-in:nth-child(5) { animation-delay: 0.2s; }
        .animate-fade-in:nth-child(6) { animation-delay: 0.25s; }
        .animate-fade-in:nth-child(7) { animation-delay: 0.3s; }
        .animate-fade-in:nth-child(8) { animation-delay: 0.35s; }

        /* ===== Glass Effect ===== */
        .glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        [data-bs-theme="dark"] .glass {
            background: rgba(255,255,255,0.03);
        }

        /* ===== User Menu Dropdown ===== */
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 200px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 40px var(--shadow-color);
            padding: 8px;
            display: none;
            z-index: 1000;
        }

        .user-dropdown.show { display: block; }

        .user-dropdown .dropdown-item {
            padding: 10px 16px;
            border-radius: 8px;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            transition: all var(--transition-speed);
        }

        .user-dropdown .dropdown-item:hover {
            background: var(--hover-bg);
        }

        .user-dropdown .dropdown-item i {
            width: 18px;
            color: var(--text-secondary);
        }

        .user-dropdown .dropdown-divider {
            border-color: var(--border-color);
            margin: 6px 0;
        }
    </style>
</head>
<body>

    <!-- ===== Sidebar ===== -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-wrench"></i>
            <div>
                <h4>MaintenancePro</h4>
                <small>Management System</small>
            </div>
        </div>

        <div class="sidebar-menu">
            <!-- HOME -->
            <div class="menu-label">Home</div>
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-line"></i> Income Dashboard
                </a>
            </div>

            <!-- OPERATIONS -->
            <div class="menu-label mt-3">Operations</div>
            <div class="nav-item">
                <a href="{{ route('admin.work-orders.index') }}" class="nav-link">
                    <i class="fas fa-clipboard-list"></i> Work Orders
                    <span class="badge danger">12</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-calendar-alt"></i> Schedule Calendar
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-check"></i> Availability
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-comments"></i> Team Chat
                    <span class="badge success">3</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-bell"></i> Notifications
                    <span class="badge danger">5</span>
                </a>
            </div>

            <!-- ASSETS & EQUIPMENT -->
            <div class="menu-label mt-3">Assets & Equipment</div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-microchip"></i> Equipment
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-snowflake"></i> HVAC
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-tools"></i> Spare Parts
                </a>
            </div>

            <!-- INTELLIGENCE -->
            <div class="menu-label mt-3">Intelligence</div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-file-alt"></i> Reports
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-robot"></i> Predictive Maintenance
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cube"></i> Digital Twin
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-calendar-check"></i> Maintenance Schedules
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-history"></i> Equipment Timeline
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-mobile-alt"></i> Mobile Workflow
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-pie"></i> Power BI
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-map-marker-alt"></i> GPS Tracking
                </a>
            </div>

            <!-- MANAGEMENT -->
            <div class="menu-label mt-3">Management</div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i> Performance Analytics
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-file-invoice"></i> Invoices
                    <span class="badge warning">4</span>
                </a>
            </div>

            <!-- USER MANAGEMENT -->
            <div class="menu-label mt-3">User Management</div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i> Technicians
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-layer-group"></i> Groups
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-friends"></i> Clients
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-clipboard"></i> Client Requests
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-crown"></i> Premium Maintenance
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-tie"></i> Manage Managers
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-cog"></i> Manage Users
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-plus"></i> Register User
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-circle"></i> My Account
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">SA</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name ?? 'System Admin' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
                <a href="#" class="text-white text-decoration-none ms-auto opacity-50" title="Sign Out">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ===== Sidebar Overlay ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ===== Main Content ===== -->
    <div class="main-content">

        <!-- ===== Top Navbar ===== -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="page-title">
                    @yield('page-title', 'Dashboard')
                    <small>@yield('page-subtitle', 'Your personal work overview')</small>
                </span>
            </div>

            <div class="nav-actions">
                <!-- Theme Toggle -->
                <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>

                <a href="#" class="btn-icon" title="Search">
                    <i class="fas fa-search"></i>
                </a>
                <a href="#" class="btn-icon" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge-dot"></span>
                </a>

                <div class="user-profile position-relative" id="userProfile">
                    <div class="avatar">SA</div>
                    <span class="d-none d-sm-inline fw-semibold small">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <i class="fas fa-chevron-down text-muted small"></i>

                    <!-- Dropdown -->
                    <div class="user-dropdown" id="userDropdown">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-shield-alt"></i> Security
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- ===== Page Content ===== -->
        <div class="container-fluid p-3 p-md-4">
            @yield('content')
        </div>

    </div>

    <!-- ===== Bootstrap JS ===== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ========================================
        // ===== كل الـ JS في ملف واحد =====
        // ========================================

        (function() {
            'use strict';

            // ===== 1. Theme Toggle (Light/Dark) =====
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const html = document.documentElement;

            // Get saved theme or default
            let currentTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-bs-theme', currentTheme);
            updateThemeIcon(currentTheme);

            themeToggle?.addEventListener('click', function() {
                const newTheme = html.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
                html.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });

            function updateThemeIcon(theme) {
                if (themeIcon) {
                    themeIcon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
                }
            }

            // ===== 2. Sidebar Toggle =====
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');

            sidebarToggle?.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar?.classList.toggle('show');
                sidebarOverlay?.classList.toggle('show');
            });

            sidebarOverlay?.addEventListener('click', function() {
                sidebar?.classList.remove('show');
                sidebarOverlay?.classList.remove('show');
            });

            // Close sidebar on resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar?.classList.remove('show');
                    sidebarOverlay?.classList.remove('show');
                }
            });

            // ===== 3. User Profile Dropdown =====
            const userProfile = document.getElementById('userProfile');
            const userDropdown = document.getElementById('userDropdown');

            userProfile?.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown?.classList.toggle('show');
            });

            document.addEventListener('click', function() {
                userDropdown?.classList.remove('show');
            });

            // ===== 4. Tooltips =====
            document.addEventListener('DOMContentLoaded', function() {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(el) {
                    return new bootstrap.Tooltip(el);
                });
            });

            // ===== 5. Console Message =====
            console.log('🚀 MaintenancePro System v2.0 Loaded Successfully!');
            console.log('🌓 Theme:', currentTheme);
            console.log('👤 User:', '{{ auth()->user()->name ?? "Guest" }}');

        })();
    </script>

    @stack('scripts')
</body>
</html>
