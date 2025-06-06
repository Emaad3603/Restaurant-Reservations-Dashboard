<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restaurant Reservations Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4A4EB2;
            --secondary-color: #4E89FF;
            --sidebar-width: 250px;
        }
        body {
            min-height: 100vh;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: white;
            z-index: 100;
            padding-top: 1rem;
        }
        .sidebar .navbar-brand {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            padding: 1rem;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0;
            padding: 0.5rem 1rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }
        .top-nav {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.04);
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        .stat-card {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            height: 100%;
        }
        .stat-card h2 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .stat-card p {
            margin-bottom: 0;
            opacity: 0.8;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-cup-hot-fill"></i> DineEase
        </a>
        <hr class="bg-white opacity-25">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            @php $user = auth()->user(); $priv = $user?->privilege; @endphp
            @if($user && ($user->admin || $priv?->hotels_tab))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}" href="{{ route('admin.hotels.index') }}">
                    <i class="bi bi-building"></i> Hotels
                </a>
            </li>
            @endif
            @if($user && ($user->admin || $priv?->restaurants_tab))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.restaurants.*') ? 'active' : '' }}" href="{{ route('admin.restaurants.index') }}">
                    <i class="bi bi-shop"></i> Restaurants
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}" href="{{ route('admin.companies.index') }}">
                    <i class="bi bi-building"></i> Companies
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}" href="{{ route('admin.menu.index') }}">
                    <i class="bi bi-list-ul"></i> Menu Management
                </a>
            </li>
            @endif
            @if($user && ($user->admin || $priv?->meal_types_tab))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.meal-types.*') ? 'active' : '' }}" href="{{ route('admin.meal-types.index') }}">
                    <i class="bi bi-clock"></i> Meal Types
                </a>
            </li>
            @endif
            @if($user && ($user->admin || $priv?->board_types_tab))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.board-types.*') ? 'active' : '' }}" href="{{ route('admin.board-types.index') }}">
                    <i class="bi bi-grid"></i> Board Types
                </a>
            </li>
            @endif
            @if($user && ($user->admin || $priv?->reservations_tab))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}" href="{{ route('admin.reservations.index') }}">
                    <i class="bi bi-calendar-check"></i> Reservations
                </a>
            </li>
            @endif
            @if($user && ($user->admin || $priv?->reports_tab))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.reservations') }}">
                    <i class="bi bi-bar-chart"></i> Reports
                </a>
            </li>
            @endif
            @if($user && $user->admin)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i> User Management
                </a>
            </li>
            @endif
        </ul>
    </div>

    <div class="main-content">
        <div class="top-nav">
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i> Admin
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content-body mt-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @stack('scripts')
</body>
</html> 