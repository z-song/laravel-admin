<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('admin.title')}} | {{ admin_trans('admin.login') }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @if(!is_null($favicon = Admin::favicon()))
        <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/fontawesome-free/css/all.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/icheck-bootstrap/icheck-bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/css/adminlte.min.css") }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    
    <script src="{{ admin_asset("vendor/laravel-admin/jquery/jquery.min.js") }}"></script>
</head>
<body class="text-sm row vh-100 overflow-hidden">

    <div class="col" {!! admin_login_page_backgroud() !!}></div>

    <div class="col d-flex justify-content-center align-items-center bg-light">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ admin_url('/') }}"><b>{{config('admin.name')}}</b></a>
            </div>

            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">{{ admin_trans('admin.login') }}</p>

                    <form action="{{ admin_url('auth/login') }}" method="post">
                        <div class="form-group">
                            @if($errors->has('username'))
                                @foreach($errors->get('username') as $message)
                                    <label class="col-form-label text-danger">
                                        <i class="fa fa-times-circle-o"></i>{{$message}}
                                    </label><br>
                                @endforeach
                            @endif
                            <div class="input-group mb-3">
                                <input type="text" class="form-control " placeholder="{{ admin_trans('admin.username') }}"
                                       name="username" value="{{ old('username') }}">
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
                                    <label class="col-form-label text-danger">
                                        <i class="fa fa-times-circle-o"></i>{{$message}}
                                    </label>
                                    <br>
                                @endforeach
                            @endif
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="{{ admin_trans('admin.password') }}"
                                       name="password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-@color">
                                    <input type="checkbox" id="remember" name="remember"
                                           value="1" {{ (!old('username') || old('remember')) ? 'checked' : '' }}>
                                    <label for="remember">
                                        {{ admin_trans('admin.remember_me') }}
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-@color btn-block">
                                    {{ admin_trans('admin.login') }}
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
    </div>
    <script type="text/javascript">
        $(function () {
            $('form input[name=username]').focus();
        });
    </script>
</body>
</html>
