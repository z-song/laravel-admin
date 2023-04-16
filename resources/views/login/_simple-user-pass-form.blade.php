<form action="{{ admin_url('auth/login') }}" method="post">
    <div class="form-group has-feedback {!! !$errors->has('username') ?: 'has-error' !!}">

        @if ($errors->has('username'))
            @foreach ($errors->get('username') as $message)
                <label class="control-label" for="inputError">
                    <i class="fa-regular fa-times-circle"></i> {{ $message }}
                </label><br>
            @endforeach
        @endif

        <input type="text" class="form-control" placeholder="{{ trans('admin.username') }}" name="username"
            value="{{ old('username') }}">
        <span class="fa-regular fa-user form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">

        @if ($errors->has('password'))
            @foreach ($errors->get('password') as $message)
                <label class="control-label" for="inputError">
                    <i class="fa-regular fa-times-circle"></i> {{ $message }}
                </label><br>
            @endforeach
        @endif

        <input type="password" class="form-control" placeholder="{{ trans('admin.password') }}" name="password">
        <span class="fa-regular fa-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-8">
            @if (config('admin.auth.remember'))
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember" value="1"
                            {{ !old('username') || old('remember') ? 'checked' : '' }}>
                        {{ trans('admin.remember_me') }}
                    </label>
                </div>
            @endif
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary btn-block">{{ trans('admin.login') }}</button>
        </div>
        <!-- /.col -->
    </div>
</form>
