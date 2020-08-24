<aside class="main-sidebar elevation-4 sidebar-{{ config('admin.theme.sidebar') }}">

    <a href="{{ admin_url('/') }}" class="brand-link">
        <img src="/vendor/laravel-admin/AdminLTE/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
