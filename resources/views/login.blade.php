<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{config('admin.title')}} | {{ trans('admin.login') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  @if(!is_null($favicon = Admin::favicon()))
  <link rel="shortcut icon" href="{{$favicon}}">
  @endif

  <!-- Bootstrap 3.3.5 -->
{{--  <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">--}}
  <!-- Font Awesome -->

    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin-v2/fontawesome-free/css/all.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin-v2/icheck-bootstrap/icheck-bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin-v2/AdminLTE/css/adminlte.min.css") }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
{{--  <!--[if lt IE 9]>--}}
<!--  <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>-->
{{--  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>--}}
{{--  <![endif]-->--}}
</head>
<body class="hold-transition login-page" @if(config('admin.login_background_image'))style="background: url({{config('admin.login_background_image')}}) no-repeat;background-size: cover;"@endif>
<div class="login-box">
  <div class="login-logo">
    <a href="{{ admin_url('/') }}"><b>{{config('admin.name')}}</b></a>
  </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ trans('admin.login') }}</p>

            <form action="{{ admin_url('auth/login') }}" method="post">
                <div class="form-group">
                    @if($errors->has('username'))
                        @foreach($errors->get('username') as $message)
                            <label class="col-form-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
                        @endforeach
                    @endif
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " placeholder="{{ trans('admin.username') }}" name="username" value="{{ old('username') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    @if($errors->has('password'))
                        @foreach($errors->get('password') as $message)
                            <label class="col-form-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
                        @endforeach
                    @endif
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="{{ trans('admin.password') }}" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" value="1" {{ (!old('username') || old('remember')) ? 'checked' : '' }}>
                            <label for="remember">
                                {{ trans('admin.remember_me') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ trans('admin.login') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.login-card-body -->
    </div>

  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ admin_asset("vendor/laravel-admin-v2/jquery/jquery.min.js")}} "></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ admin_asset('vendor/laravel-admin-v2/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- iCheck -->
<script src="{{ admin_asset("vendor/laravel-admin-v2/AdminLTE/js/adminlte.min.js")}}"></script>

</body>
</html>
