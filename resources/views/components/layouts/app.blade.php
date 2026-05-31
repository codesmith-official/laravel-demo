<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel Demo') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php($authUser = request()->user('api'))
<body class="admin-body">
    <div class="admin-shell">
        <aside class="sidebar" id="sidebar">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <span class="brand-icon"><i class="bi bi-shield-lock"></i></span>
                <span>Admin</span>
            </a>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </nav>
        </aside>

        <div class="admin-main">
            <header class="admin-header">
                <button class="btn btn-icon d-lg-none" type="button" data-sidebar-toggle aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>

                <div class="ms-auto dropdown">
                    <button class="btn avatar-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ $authUser?->profile_photo_path ? asset('storage/'.$authUser->profile_photo_path) : asset('images/default-avatar.svg') }}" alt="User avatar">
                        <span class="d-none d-sm-inline">{{ $authUser?->full_name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Edit Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.password.edit') }}"><i class="bi bi-key me-2"></i>Reset Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <main class="content">
                @include('partials.alerts')
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
