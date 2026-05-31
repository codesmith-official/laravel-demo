<x-layouts.app title="Dashboard">
    <div class="page-heading">
        <div>
            <h1>Dashboard</h1>
            <p>Overview of the admin workspace.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('users.index') }}" class="metric-card">
                <span class="metric-icon"><i class="bi bi-people"></i></span>
                <span class="metric-label">Total Users</span>
                <strong>{{ number_format($totalUsers) }}</strong>
            </a>
        </div>
    </div>
</x-layouts.app>
