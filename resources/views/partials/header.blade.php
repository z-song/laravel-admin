<!-- AdminLTE 4 Header -->
<header class="app-header navbar navbar-expand navbar-light" role="banner">
    <div class="container-fluid">
        
        <!-- Brand Logo -->
        <a href="{{ admin_url('/') }}" class="navbar-brand d-flex align-items-center">
            <span class="brand-text d-none d-lg-inline">{!! config('admin.logo', config('admin.name')) !!}</span>
            <span class="brand-text-mini d-lg-none">{!! config('admin.logo-mini', config('admin.name')) !!}</span>
        </a>

        <!-- Sidebar toggle button -->
        <button class="sidebar-toggle btn btn-link d-md-none" type="button" 
                aria-label="Toggle navigation" aria-expanded="true">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Left navbar links -->
        <ul class="navbar-nav d-none d-lg-flex me-auto">
            {!! Admin::getNavbar()->render('left') !!}
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ms-auto">
            
            {!! Admin::getNavbar()->render() !!}

            <!-- Dark mode toggle -->
            <li class="nav-item">
                <button class="nav-link btn btn-link dark-mode-toggle" type="button" 
                        aria-label="Toggle dark mode">
                    <i class="fas fa-moon"></i>
                </button>
            </li>

            <!-- User Account Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Admin::user()->avatar }}" 
                         class="user-image rounded-circle me-2" 
                         alt="User Image" 
                         width="32" height="32">
                    <span class="d-none d-md-inline">{{ Admin::user()->name }}</span>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end">
                    <!-- User info header -->
                    <li class="dropdown-header bg-light p-3 text-center">
                        <img src="{{ Admin::user()->avatar }}" 
                             class="rounded-circle mb-2" 
                             alt="User Image" 
                             width="64" height="64">
                        <h6 class="mb-1">{{ Admin::user()->name }}</h6>
                        <small class="text-muted">Member since {{ Admin::user()->created_at->format('M Y') }}</small>
                    </li>
                    
                    <li><hr class="dropdown-divider"></li>
                    
                    <!-- Settings link -->
                    <li>
                        <a class="dropdown-item" href="{{ admin_url('auth/setting') }}">
                            <i class="fas fa-cog me-2"></i>
                            {{ trans('admin.setting') }}
                        </a>
                    </li>
                    
                    <!-- Logout link -->
                    <li>
                        <a class="dropdown-item text-danger" href="{{ admin_url('auth/logout') }}">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            {{ trans('admin.logout') }}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>