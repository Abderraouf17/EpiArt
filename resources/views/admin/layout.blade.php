<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم - EpiArt')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:600,700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #8B3A3A;
            --primary-dark: #722F37;
            --secondary: #6b21a8;
        }
        body {
            direction: rtl;
            font-family: 'Instrument Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(180deg, #722F37 0%, #8B3A3A 50%, #5a1e25 100%);
            min-height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            width: 280px;
            box-shadow: -4px 0 20px rgba(0,0,0,0.15);
        }
        .sidebar .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: white;
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
            transition: opacity 0.3s;
        }
        .sidebar .logo:hover {
            opacity: 0.9;
        }
        .sidebar .logo img {
            height: 50px;
            width: auto;
        }
        .sidebar .logo span {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255,255,255,0.9);
            padding: 1rem 1.5rem;
            margin: 0.25rem 0.5rem;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 0.95rem;
            text-decoration: none;
        }
        .sidebar nav a:hover,
        .sidebar nav a.active {
            color: white;
            background: rgba(255,255,255,0.15);
            transform: translateX(-5px);
        }
        .main-content {
            margin-right: 260px;
            padding: 2rem;
            min-height: 100vh;
        }
        .navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .btn {
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #dc2626;
            color: white;
        }
        .btn-danger:hover {
            background: #991b1b;
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th {
            background: #f3f4f6;
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }
        .table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .table tr:hover {
            background: #f9fafb;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 1rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .alert-error {
            background: #fee2e2;
            color: #7f1d1d;
            border: 1px solid #fca5a5;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background: #fee2e2;
            color: #7f1d1d;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
        }
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .modal-content {
            background: white;
            border-radius: 8px;
            width: 90%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h3 {
            margin: 0;
            color: var(--primary);
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #6b7280;
            line-height: 1;
            padding: 0;
        }
        .close-btn:hover {
            color: #000;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="/" class="logo">
            <img src="/images/logo.png" alt="EpiArt">
            <span>EpiArt</span>
        </a>
        <nav>
            <a href="/admin/dashboard" class="@if(request()->is('admin/dashboard')) active @endif">📊 لوحة التحكم</a>
            <a href="/admin/users" class="@if(request()->is('admin/users*')) active @endif">👥 المستخدمين</a>
            <a href="/admin/products" class="@if(request()->is('admin/products*')) active @endif">📦 المنتجات</a>
            <a href="/admin/categories" class="@if(request()->is('admin/categories*')) active @endif">📂 الفئات</a>
            <a href="/admin/orders" class="@if(request()->is('admin/orders*')) active @endif">🛒 الطلبات</a>
            <a href="/admin/shipping" class="@if(request()->is('admin/shipping*')) active @endif">
                <span>🚚</span> الشحن
            </a>
            <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.2); margin: 1rem 0.5rem;">
            <a href="/" target="_blank" onclick="window.open('/', '_blank', 'noopener,noreferrer'); return false;">
                <span>🌐</span> عرض الموقع
            </a>
            <form method="POST" action="/logout" style="display: inline; width: 100%;">
                @csrf
                <button type="submit" style="width: 100%; text-align: right; background: none; border: none; color: rgba(255,255,255,0.9); padding: 1rem 1.5rem; cursor: pointer; border-radius: 8px; margin: 0.25rem 0.5rem; transition: all 0.3s; display: flex; align-items: center; gap: 0.75rem; font-size: 0.95rem;">
                    <span>🚪</span> تسجيل الخروج
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        <div class="navbar">
            <div>
                <h2>@yield('header', 'لوحة التحكم')</h2>
            </div>
            <div>
                <span>مرحباً، {{ auth()->user()->name }}</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
    <!-- Message Modal -->
    <div id="messageModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 400px; text-align: center; padding: 2rem;">
            <div id="messageIcon" style="font-size: 3rem; margin-bottom: 1rem;"></div>
            <h3 id="messageTitle" style="margin-bottom: 1rem;"></h3>
            <p id="messageText" style="color: #6b7280; margin-bottom: 2rem;"></p>
            <button onclick="closeMessageModal()" class="btn btn-primary">حسناً</button>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 400px; text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">❓</div>
            <h3 style="margin-bottom: 1rem;">تأكيد الإجراء</h3>
            <p id="confirmText" style="color: #6b7280; margin-bottom: 2rem;"></p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <button id="confirmCancelBtn" class="btn btn-secondary">إلغاء</button>
                <button id="confirmOkBtn" class="btn btn-primary">تأكيد</button>
            </div>
        </div>
    </div>

    <script>
    function showModalAlert(message, type = 'info') {
        const modal = document.getElementById('messageModal');
        const icon = document.getElementById('messageIcon');
        const title = document.getElementById('messageTitle');
        const text = document.getElementById('messageText');
        
        if (type === 'error') {
            icon.textContent = '❌';
            title.textContent = 'خطأ';
            title.style.color = '#dc2626';
        } else if (type === 'success') {
            icon.textContent = '✅';
            title.textContent = 'نجاح';
            title.style.color = '#059669';
        } else {
            icon.textContent = 'ℹ️';
            title.textContent = 'تنبيه';
            title.style.color = '#3b82f6';
        }
        
        text.textContent = message;
        modal.style.display = 'flex';
    }

    function closeMessageModal() {
        document.getElementById('messageModal').style.display = 'none';
    }

    function showModalConfirm(message) {
        return new Promise((resolve) => {
            const modal = document.getElementById('confirmModal');
            const text = document.getElementById('confirmText');
            const okBtn = document.getElementById('confirmOkBtn');
            const cancelBtn = document.getElementById('confirmCancelBtn');
            
            text.textContent = message;
            modal.style.display = 'flex';
            
            const handleOk = () => {
                modal.style.display = 'none';
                cleanup();
                resolve(true);
            };
            
            const handleCancel = () => {
                modal.style.display = 'none';
                cleanup();
                resolve(false);
            };
            
            const cleanup = () => {
                okBtn.removeEventListener('click', handleOk);
                cancelBtn.removeEventListener('click', handleCancel);
            };
            
            okBtn.addEventListener('click', handleOk);
            cancelBtn.addEventListener('click', handleCancel);
        });
    }
    
    // Override standard alert
    window.originalAlert = window.alert;
    window.alert = function(message) {
        showModalAlert(message);
    };

    // Global confirmDelete using modal
    window.confirmDelete = function(message) {
        return showModalConfirm(message);
    };
    </script>
</body>
</html>
