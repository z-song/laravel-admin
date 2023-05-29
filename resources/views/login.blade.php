<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('admin.title') }} | {{ trans('admin.login') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @if (!is_null($favicon = Admin::favicon()))
        <link rel="shortcut icon" href="{{ $favicon }}">
    @endif

    {!! Admin::css() !!}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition login-page"
    @if (config('admin.login_background_image')) style="background: url({{ config('admin.login_background_image') }}) no-repeat;background-size: cover;" @endif>
    <div class="login-box">
        <div class="@if (config('admin.login_background_image')) login-box-body @endif">
            <h1>{{ trans('admin.greeting') }}</h1>
            <p class="login-box-msg">{{ trans('admin.welcome') }}</p>

            @if (config('admin.auth.mode') === 'otp')
                @if(session('mobile'))
                    @include('admin::login._login-using-otp-form')
                @else
                    @include('admin::login._req-otp-form')
                @endif
            @else
                @include('admin::login._simple-user-pass-form')
            @endif

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="{{ admin_asset('vendor/laravel-admin/AdminLTE/plugins/jQuery/jquery-3.6.1.min.js') }}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ admin_asset('vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js') }}"></script>
    @if (config('admin.auth.remember'))
        <!-- iCheck -->
        <script src="{{ admin_asset('vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>
        <script>
            $(function() {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    @endif
</body>

</html>
