<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/dist/css/AdminLTE.min.css") }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/iCheck/square/blue.css") }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>众筹</b>100</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">登陆</p>

    <form action="/admin/login" method="post">
      <div class="form-group has-feedback">
        <input type="input" class="form-control" placeholder="账号" name="username">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="密码" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        {{--<div class="col-xs-8">--}}
          {{--<div class="checkbox icheck">--}}
            {{--<label>--}}
              {{--<input type="checkbox"> Remember Me--}}
            {{--</label>--}}
          {{--</div>--}}
        {{--</div>--}}
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">登陆</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    {{--<div class="social-auth-links text-center">--}}
      {{--<p>- OR -</p>--}}
      {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using--}}
        {{--Facebook</a>--}}
      {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using--}}
        {{--Google+</a>--}}
    {{--</div>--}}
    {{--<!-- /.social-auth-links -->--}}

    {{--<a href="#">I forgot my password</a><br>--}}
    {{--<a href="register.html" class="text-center">Register a new membership</a>--}}

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset("/bower_components/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js")}} "></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ asset("/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<!-- iCheck -->
<script src="{{ asset("/bower_components/AdminLTE/plugins/iCheck/icheck.min.js")}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
