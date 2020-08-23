<aside class="main-sidebar elevation-4 sidebar-{{ config('admin.theme.sidebar') }}">

    <a href="{{ admin_url('/') }}" class="brand-link navbar-{{ config('admin.theme.navbar.bg') }} navbar-{{ config('admin.theme.navbar.color') }}">
        <img src="/vendor/laravel-admin-v2/AdminLTE/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight">{!! config('admin.logo', config('admin.name')) !!}</span>
    </a>

{{--    <a href="/" class="brand-link logo-switch navbar-light text-info">--}}
{{--        <img src="https://laravel-admin.org/images/logo.png" alt="AdminLTE Docs Logo Small" class="brand-image-xl logo-xs" style="height: 29px;margin-top: 1px;margin-left: 13px;">--}}
{{--        <span>--}}
{{--            <img src="https://laravel-admin.org/images/logo.png" alt="AdminLTE Docs Logo Large" class="brand-image-xs logo-xl" style="left: 30px">--}}
{{--            <span style="position: absolute;left:58px;" class="text-lg">aravel-admin</span>--}}
{{--        </span>--}}
{{--    </a>--}}

    <!-- sidebar: style can be found in sidebar.less -->
    <div class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Admin::user()->avatar }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Admin::user()->name }}</a>
            </div>
        </div>

        @if(0 && config('admin.enable_menu_search'))
        <!-- search form (Optional) -->
        <form class="sidebar-form" style="overflow: initial;" onsubmit="return false;">
            <div class="input-group">
                <input type="text" autocomplete="off" class="form-control autocomplete" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                <ul class="dropdown-menu" role="menu" style="min-width: 210px;max-height: 300px;overflow: auto;">
                    @foreach(Admin::menuLinks() as $link)
                    <li>
                        <a href="{{ admin_url($link['uri']) }}"><i class="fa {{ $link['icon'] }}"></i>{{ admin_trans($link['title']) }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </form>
        <!-- /.search form -->
        @endif

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @each('admin::partials.menu', Admin::menu(), 'item')
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
