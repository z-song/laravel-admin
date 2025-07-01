<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" data-bs-theme="{{ config('admin.theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Admin::title() }} @if($header) | {{ $header }}@endif</title>
    
    <!-- Responsive viewport meta tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO and accessibility improvements -->
    <meta name="description" content="{{ config('admin.description', 'Laravel Admin Panel') }}">
    <meta name="theme-color" content="#007bff">

    @if(!is_null($favicon = Admin::favicon()))
    <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    <!-- AdminLTE 4 + Bootstrap 5 CSS -->
    @if(config('admin.vite.enabled', false))
        @vite(['resources/assets-vite/css/adminlte4.css'])
    @endif
    
    <!-- Legacy CSS fallback -->
    {!! Admin::css() !!}

    <!-- Preload critical JavaScript -->
    <link rel="preload" href="{{ Admin::jQuery() }}" as="script">
    
    <script src="{{ Admin::jQuery() }}"></script>
    {!! Admin::headerJs() !!}

</head>

<body class="app-wrapper {{ config('admin.layout_class', '') }}" data-admin-theme="{{ config('admin.theme', 'light') }}"

@if($alert = config('admin.top_alert'))
    <div class="alert alert-warning alert-dismissible fade show m-0 rounded-0 border-0 text-center" role="alert">
        {!! $alert !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Skip navigation link for accessibility -->
<a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>

<div class="app-layout">

    @include('admin::partials.header')

    @include('admin::partials.sidebar')

    <main class="app-main" id="main-content" role="main">
        <div class="content-header">
            <div class="container-fluid">
                @if(isset($header))
                    <h1 class="m-0">{{ $header }}</h1>
                @endif
            </div>
        </div>
        
        <div class="content" id="pjax-container">
            {!! Admin::style() !!}
            <div id="app" class="container-fluid">
                @yield('content')
            </div>
            {!! Admin::script() !!}
            {!! Admin::html() !!}
        </div>
    </main>

    @include('admin::partials.footer')

</div>

<!-- Back to top button -->
<button id="totop" class="btn btn-primary position-fixed" 
        style="bottom: 20px; right: 20px; z-index: 1000; display: none; border-radius: 50%; width: 50px; height: 50px;"
        title="Go to top" aria-label="Go to top">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Global JavaScript configuration -->
<script>
    // Laravel Admin configuration
    window.LA = window.LA || {};
    LA.token = "{{ csrf_token() }}";
    LA.user = @json($_user_);
    LA.config = {
        locale: "{{ config('app.locale') }}",
        theme: "{{ config('admin.theme', 'light') }}",
        viteEnabled: {{ config('admin.vite.enabled', false) ? 'true' : 'false' }}
    };
</script>

<!-- AdminLTE 4 + Bootstrap 5 JavaScript -->
@if(config('admin.vite.enabled', false))
    @vite(['resources/assets-vite/js/adminlte4.js'])
@endif

<!-- Legacy JavaScript fallback -->
{!! Admin::js() !!}

</body>
</html>
