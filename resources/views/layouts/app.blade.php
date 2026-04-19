<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f5fb8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="HRGA Activity">
    <title>{{ $title ?? 'Daily Activity' }} | HRGA</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/calendar-192.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <style>
        :root {
            --blue-main: #0f5fb8;
            --blue-deep: #084887;
            --blue-soft: #e8f2ff;
            --ink: #1f2937;
            --muted: #64748b;
            --line: #dbeafe;
        }

        body {
            color: var(--ink);
            font-size: 15px;
        }

        .brand-link,
        .main-sidebar {
            background: linear-gradient(180deg, #073b73 0%, var(--blue-deep) 42%, var(--blue-main) 100%);
        }

        .brand-link {
            display: flex;
            align-items: center;
            min-height: 64px;
            padding: .75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .14);
            overflow: hidden;
        }

        .brand-link .brand-text,
        .brand-link .brand-image {
            color: #fff;
        }

        .brand-logo-frame {
            flex: 0 0 50px;
            width: 50px;
            height: 38px;
            border-radius: .55rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .95);
            box-shadow: 0 .35rem 1rem rgba(0, 0, 0, .12);
            overflow: hidden;
            padding: .18rem;
        }

        .brand-logo {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .brand-copy {
            min-width: 0;
            margin-left: .75rem;
            line-height: 1.1;
        }

        .brand-copy strong,
        .brand-copy small,
        .sidebar-user-meta,
        .sidebar-user-name {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .brand-copy small {
            color: rgba(255, 255, 255, .64);
            font-size: .75rem;
            margin-top: .2rem;
        }

        .sidebar {
            padding: .8rem .45rem 1rem;
        }

        .sidebar-user-card {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .8rem;
            margin: .2rem .35rem 1rem;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: .75rem;
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .sidebar-avatar {
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: var(--blue-main);
            font-weight: 800;
        }

        .sidebar-user-info {
            min-width: 0;
        }

        .sidebar-user-name {
            color: #fff;
            font-weight: 700;
            line-height: 1.2;
        }

        .sidebar-user-meta {
            color: rgba(255, 255, 255, .68);
            font-size: .78rem;
            margin-top: .18rem;
        }

        .nav-sidebar .nav-header {
            margin: .85rem .75rem .35rem;
            padding: 0;
            color: rgba(255, 255, 255, .58) !important;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .08em;
        }

        .main-sidebar .nav-link {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, .86);
            border-radius: .55rem;
            min-height: 42px;
            margin: .15rem .35rem;
            padding: .68rem .8rem;
            line-height: 1.1;
        }

        .main-sidebar .nav-link.active,
        .main-sidebar .nav-link:hover {
            background: rgba(255, 255, 255, .18);
            color: #fff;
            box-shadow: inset 3px 0 0 #fff;
        }

        .nav-sidebar .nav-icon {
            width: 1.45rem;
            margin-right: .55rem !important;
            text-align: center;
            font-size: .95rem;
        }

        .nav-sidebar .nav-link p {
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .navbar-white {
            border-bottom: 1px solid var(--line);
        }

        .content-wrapper {
            background: #f5f8fc;
        }

        .content-header h1 {
            font-size: 1.55rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .breadcrumb {
            background: transparent;
            padding: .25rem 0;
            margin-bottom: 0;
        }

        .btn-primary,
        .page-item.active .page-link {
            background-color: var(--blue-main);
            border-color: var(--blue-main);
        }

        .text-primary {
            color: var(--blue-main) !important;
        }

        .small-box {
            border-radius: .5rem;
            overflow: hidden;
        }

        .card {
            border-radius: .5rem;
            border: 1px solid #e5edf7;
            box-shadow: 0 .2rem .7rem rgba(15, 95, 184, .06);
        }

        .card-header {
            min-height: 54px;
        }

        .card-title {
            float: none;
            margin-bottom: 0;
        }

        .card-header .btn,
        .card-header .btn-group {
            flex: 0 0 auto;
        }

        .progress {
            height: .7rem;
            border-radius: 999px;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .main-footer {
            border-top: 1px solid #dbeafe;
            color: #5f6f82;
            font-size: .9rem;
        }

        .modal-backdrop.show {
            opacity: .42;
        }

        .modal-content {
            border: 0;
            border-radius: .75rem;
            box-shadow: 0 1.25rem 3rem rgba(15, 23, 42, .24);
            overflow: hidden;
        }

        .modal-header {
            align-items: center;
            background: linear-gradient(135deg, var(--blue-deep), var(--blue-main));
            color: #fff;
            border-bottom: 0;
            padding: 1rem 1.25rem;
        }

        .modal-title {
            font-weight: 800;
            line-height: 1.2;
        }

        .modal-header .close {
            color: #fff;
            opacity: .9;
            text-shadow: none;
        }

        .modal-body {
            padding: 1.25rem;
        }

        .modal-footer {
            background: #f8fbff;
            border-top: 1px solid var(--line);
            padding: .9rem 1.25rem;
        }

        .modal .form-group label {
            color: #334155;
            font-size: .88rem;
            font-weight: 700;
        }

        .modal .form-control {
            border-color: #d7e3f2;
            border-radius: .45rem;
        }

        .table-action-group {
            display: inline-flex;
            gap: .35rem;
            justify-content: flex-end;
            align-items: center;
            white-space: nowrap;
        }

        .table-action-group form {
            display: inline-flex;
        }

        .notification-menu {
            width: min(92vw, 360px);
            padding: 0;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: .65rem;
            box-shadow: 0 .8rem 2rem rgba(15, 23, 42, .14);
        }

        .navbar .notification-toggle {
            position: relative;
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: .5rem;
            border-radius: .65rem;
            color: #334155;
        }

        .navbar .notification-toggle:hover {
            background: #eef6ff;
            color: var(--blue-main);
        }

        .navbar .notification-toggle .fa-bell {
            font-size: 1.08rem;
        }

        .notification-count {
            position: absolute;
            top: 4px;
            right: 3px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f59e0b;
            color: #111827;
            border: 2px solid #fff;
            font-size: .68rem;
            font-weight: 800;
            line-height: 1;
        }

        .navbar-date {
            min-height: 42px;
            padding: 0 .75rem;
            border-left: 1px solid #e5edf7;
            border-right: 1px solid #e5edf7;
        }

        .notification-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .8rem 1rem;
            background: #f8fbff;
            border-bottom: 1px solid var(--line);
            font-weight: 800;
        }

        .notification-item {
            display: flex;
            gap: .75rem;
            padding: .75rem 1rem;
            color: var(--ink);
            border-bottom: 1px solid #eef4fb;
        }

        .notification-item:hover {
            background: #f8fbff;
            color: var(--ink);
            text-decoration: none;
        }

        .notification-title {
            display: block;
            font-weight: 700;
            line-height: 1.25;
        }

        .notification-meta {
            display: block;
            color: var(--muted);
            font-size: .82rem;
            margin-top: .15rem;
        }

        .sidebar-collapse .brand-copy,
        .sidebar-collapse .sidebar-user-info,
        .sidebar-collapse .nav-sidebar .nav-link p,
        .sidebar-collapse .nav-sidebar .nav-header {
            display: none !important;
        }

        .sidebar-collapse .brand-link {
            justify-content: center;
            padding-left: .5rem;
            padding-right: .5rem;
        }

        .sidebar-collapse .brand-logo-frame {
            margin: 0;
        }

        .sidebar-collapse .sidebar-user-card {
            justify-content: center;
            padding: .65rem .35rem;
            margin-left: .25rem;
            margin-right: .25rem;
        }

        .sidebar-collapse .main-sidebar .nav-link {
            justify-content: center;
            padding-left: .5rem;
            padding-right: .5rem;
        }

        .sidebar-collapse .nav-sidebar .nav-icon {
            margin-right: 0 !important;
        }

        @media (max-width: 575.98px) {
            .content-header h1 {
                font-size: 1.3rem;
            }

            .btn-group-responsive {
                display: grid;
                gap: .5rem;
            }

            .content-header .breadcrumb {
                float: none !important;
                margin-top: .35rem;
            }

            .modal-dialog {
                margin: .65rem;
            }

            .navbar .notification-toggle {
                margin-right: .15rem;
            }

            .notification-menu {
                width: calc(100vw - 1rem);
                right: .5rem !important;
                left: auto !important;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link notification-toggle" data-toggle="dropdown" href="#" aria-label="Notifikasi">
                    <i class="far fa-bell"></i>
                    @if (($headerNotificationCount ?? 0) > 0)
                        <span class="notification-count">{{ $headerNotificationCount > 9 ? '9+' : $headerNotificationCount }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right notification-menu">
                    <div class="notification-header">
                        <span>Notifikasi</span>
                        <span class="badge badge-primary">{{ $headerNotificationCount ?? 0 }}</span>
                    </div>
                    @forelse (($headerNotifications ?? collect()) as $notification)
                        <a href="{{ $notification['url'] }}" class="notification-item">
                            <i class="{{ $notification['icon'] }} {{ $notification['color'] }} mt-1"></i>
                            <span>
                                <span class="notification-title">{{ $notification['title'] }}</span>
                                <span class="notification-meta">{{ $notification['meta'] }}</span>
                            </span>
                        </a>
                    @empty
                        <div class="px-3 py-4 text-center text-muted">Tidak ada notifikasi.</div>
                    @endforelse
                    <div class="p-2 text-center bg-light">
                        <a href="{{ route('dashboard') }}" class="small font-weight-bold">Lihat dashboard</a>
                    </div>
                </div>
            </li>
            <li class="nav-item d-none d-md-flex align-items-center mr-3 text-muted navbar-date">
                <i class="far fa-calendar-alt mr-2"></i>{{ now()->translatedFormat('d M Y') }}
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-label="User menu">
                    <i class="far fa-user-circle mr-1"></i>{{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <span class="dropdown-item-text text-muted">{{ auth()->user()->email }}</span>
                    <span class="dropdown-item-text">
                        <span class="badge badge-primary">{{ auth()->user()->isAdmin() ? 'Admin' : 'Karyawan' }}</span>
                    </span>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('password.edit') }}" class="dropdown-item">
                        <i class="fas fa-key mr-2"></i>Ganti Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <span class="brand-logo-frame">
                <img src="{{ asset('images/SCM-transparent.png') }}" alt="SCM" class="brand-logo">
            </span>
            <span class="brand-copy">
                <strong>HRGA Activity</strong>
                <small>PT Sulawesi Cahaya Mineral</small>
            </span>
        </a>

        <div class="sidebar">
            <div class="sidebar-user-card">
                <span class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <div class="sidebar-user-info">
                    <a href="{{ route('dashboard') }}" class="sidebar-user-name">{{ auth()->user()->name }}</a>
                    <span class="sidebar-user-meta">{{ auth()->user()->isAdmin() ? 'Superadmin' : 'Karyawan' }}</span>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-header text-white-50">DASHBOARD</li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>Summary</p>
                        </a>
                    </li>
                    <li class="nav-header text-white-50">AKTIVITAS</li>
                    <li class="nav-item">
                        <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Daily Activity</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('activities.create') }}" class="nav-link">
                            <i class="nav-icon fas fa-plus-circle"></i>
                            <p>Input Aktivitas</p>
                        </a>
                    </li>
                    <li class="nav-header text-white-50">PERENCANAAN</li>
                    <li class="nav-item">
                        <a href="{{ route('calendar.index') }}" class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                            <i class="nav-icon far fa-calendar-alt"></i>
                            <p>Kalender</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') || request()->routeIs('project-tasks.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-project-diagram"></i>
                            <p>Project Agile</p>
                        </a>
                    </li>
                    @if (auth()->user()->isAdmin())
                        <li class="nav-header text-white-50">ADMIN</li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-header text-white-50">AKUN</li>
                    <li class="nav-item">
                        <a href="{{ route('password.edit') }}" class="nav-link {{ request()->routeIs('password.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Ganti Password</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-7">
                        <h1>{{ $pageTitle ?? 'Daily Activity' }}</h1>
                    </div>
                    <div class="col-sm-5">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? 'Daily Activity' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid pb-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
        <div><strong>HRGA Daily Activity</strong> &copy; {{ date('Y') }}</div>
        <div class="mt-1 mt-sm-0">PT Sulawesi Cahaya Mineral</div>
    </footer>
</div>

<div class="modal fade" id="confirmActionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Aksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="confirmActionText">Lanjutkan aksi ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmActionButton">
                    <i class="fas fa-check mr-1"></i>Ya, lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
    let pendingConfirmForm = null;

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!form.matches('[data-confirm]') || form.dataset.confirmed === 'true') {
            return;
        }

        event.preventDefault();
        pendingConfirmForm = form;
        document.getElementById('confirmActionText').textContent = form.dataset.confirm || 'Lanjutkan aksi ini?';
        $('#confirmActionModal').modal('show');
    });

    document.getElementById('confirmActionButton').addEventListener('click', function () {
        if (!pendingConfirmForm) {
            return;
        }

        pendingConfirmForm.dataset.confirmed = 'true';
        $('#confirmActionModal').modal('hide');
        pendingConfirmForm.submit();
    });
</script>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(function () {});
        });
    }
</script>
@stack('scripts')
</body>
</html>
