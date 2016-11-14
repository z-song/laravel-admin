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

    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/select2/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap-fileinput/css/fileinput.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/iCheck/all.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/ionslider/ion.rangeSlider.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/plugins/ionslider/ion.rangeSlider.skinNice.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/codemirror/lib/codemirror.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/nestable/nestable.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap3-editable/css/bootstrap-editable.css") }}">

    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/dist/css/AdminLTE.min.css") }}">

    <!-- REQUIRED JS SCRIPTS -->
    <script src="{{ asset ("/packages/admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/AdminLTE/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/AdminLTE/dist/js/app.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/jquery-pjax/jquery.pjax.js") }}"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

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

    @include('admin::partials.control-sidebar')

</div>

<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset ("/packages/admin/AdminLTE/plugins/select2/select2.full.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/bootstrap-fileinput/js/fileinput.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/moment/min/moment-with-locales.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/number-input/bootstrap-number-input.js") }}"></script>
<script src="{{ asset ("/packages/admin/AdminLTE/plugins/iCheck/icheck.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/bootstrap-switch/dist/js/bootstrap-switch.min.js") }}"></script>
<script src="//cdn.ckeditor.com/4.5.10/standard/ckeditor.js"></script>
<script src="{{ asset ("/packages/admin/AdminLTE/plugins/chartjs/Chart.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/AdminLTE/plugins/ionslider/ion.rangeSlider.min.js") }}"></script>

<script src="{{ asset ("/packages/admin/codemirror/lib/codemirror.js") }}"></script>
<script src="{{ asset ("/packages/admin/codemirror/mode/javascript/javascript.js") }}"></script>
<script src="{{ asset ("/packages/admin/codemirror/addon/edit/matchbrackets.js") }}"></script>
<script src="{{ asset ("/packages/admin/nestable/jquery.nestable.js") }}"></script>
<script src="{{ asset ("/packages/admin/noty/jquery.noty.packaged.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/bootstrap3-editable/js/bootstrap-editable.min.js") }}"></script>

@if(config('app.locale') == 'zh_CN')
<script src="{{ asset ("http://map.qq.com/api/js?v=2.exp") }}"></script>
@else
<script src="{{ asset ("https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false") }}"></script>
@endif

<script>

    $.fn.editable.defaults.params = function (params) {
        params._token = '{{ csrf_token() }}';
        params._editable = 1;
        params._method = 'PUT';
        return params;
    };

    $.noty.defaults.layout = 'topRight';
    $.noty.defaults.theme = 'relax';

    $(document).pjax('a:not(a[target="_blank"])', {
        timeout: 5000,
        container: '#pjax-container'
    });

    $(document).on('submit', 'form[pjax-container]', function(event) {
        $.pjax.submit(event, '#pjax-container')
    });

    $(document).on('pjax:error', function(event, xhr) {

        var message = '';

        try{
            response = JSON.parse(xhr.responseText);
            message = response.message || 'error';
        }catch(e){
            message = (xhr.status == 404) ? 'Page Not found' : 'error';

            noty({
                text: "<strong>Warning!</strong><br/>"+message,
                type:'error',
                timeout: 5000
            });
            return false;
        }

        if (message) {
            noty({
                text: "<strong>Warning!</strong><br/>"+message,
                type:'warning',
                timeout: 5000
            });
        }

        return false;
    });

    $(document).on("pjax:popstate", function() {

        $(document).one("pjax:end", function(event) {
            $(event.target).find("script[data-exec-on-popstate]").each(function() {
                $.globalEval(this.text || this.textContent || this.innerHTML || '');
            });
        });
    });

</script>

</body>
</html>
