<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Admin::title() }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("/packages/admin/font-awesome/css/font-awesome.min.css") }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/dist/css/skins/" . config('admin.skin') .".min.css") }}">

    {!! Admin::css() !!}
    <link rel="stylesheet" href="{{ asset("/packages/admin/nestable/nestable.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/toastr/build/toastr.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap3-editable/css/bootstrap-editable.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/google-fonts/fonts.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/dist/css/AdminLTE.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/nprogress/nprogress.min.css") }}">

    <!-- REQUIRED JS SCRIPTS -->
    <script src="{{ asset ("/packages/admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/AdminLTE/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/AdminLTE/dist/js/app.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/jquery-pjax/jquery.pjax.js") }}"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .file-drag-handle{
            display:none;
        }
    </style>

</head>

<body class="hold-transition {{config('admin.skin')}} {{join(' ', config('admin.layout'))}}">
<div class="wrapper">

    @include('admin::partials.header')

    @include('admin::partials.sidebar')

    <div class="content-wrapper" id="pjax-container">
        @yield('content')
        {!! Admin::script() !!}
    </div>

    @include('admin::partials.footer')

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset ("/packages/admin/AdminLTE/plugins/chartjs/Chart.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/nestable/jquery.nestable.js") }}"></script>
<script src="{{ asset ("/packages/admin/toastr/build/toastr.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/bootstrap3-editable/js/bootstrap-editable.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/nprogress/nprogress.min.js") }}"></script>

{!! Admin::js() !!}

<script>

    function LA() {}
    LA.token = "{{ csrf_token() }}";

    $.fn.editable.defaults.params = function (params) {
        params._token = '{{ csrf_token() }}';
        params._editable = 1;
        params._method = 'PUT';
        return params;
    };

    toastr.options = {
        "newestOnTop": true,
        "closeButton": true,
        "showMethod": 'slideDown',
        "preventDuplicates": true,
        "timeOut": 4000
    };

    $.pjax.defaults.timeout = 5000;
    $.pjax.defaults.maxCacheLength = 0;
    $(document).pjax('a:not(a[target="_blank"])', {
        container: '#pjax-container'
    });

    $(document).on('submit', 'form[pjax-container]', function(event) {
        $.pjax.submit(event, '#pjax-container')
    });

    $(document).on({
        "pjax:start": function() { NProgress.start();},
        "pjax:end": function() { NProgress.done(); },
        "ajaxStart": function() { NProgress.start();},
        "ajaxStop": function() { NProgress.done(); }
    });

    $(document).on("pjax:popstate", function() {

        $(document).one("pjax:end", function(event) {
            $(event.target).find("script[data-exec-on-popstate]").each(function() {
                $.globalEval(this.text || this.textContent || this.innerHTML || '');
            });
        });
    });
    
    $(document).on('pjax:send', function(xhr) {
        if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
            $submit_btn = $('form[pjax-container] :submit');
            if($submit_btn) {
                $submit_btn.button('loading')
            }
        }
    })
    
    $(document).on('pjax:complete', function(xhr) {
        if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
            $submit_btn = $('form[pjax-container] :submit');
            if($submit_btn) {
                $submit_btn.button('reset')
            }
        }
    })

    $(function(){
        function activeMenu( li) {
            li.addClass('active').siblings().removeClass('active').find('ul.treeview-menu').removeClass('menu-open');
            return li;
        }
        $('.sidebar-menu li:not(.treeview) > a').off('click').on('click', function(){
            var li = $(this).closest('li');
            while( activeMenu(li).size() && (! li.parent().hasClass('sidebar-menu'))){
                li = li.parent().closest('li');
            }
        });
    });

</script>

</body>
</html>
