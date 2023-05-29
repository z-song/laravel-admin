<form action="{{ admin_url('auth/login') }}" method="post">
    <div class="form-group has-feedback {!! !$errors->has('mobile') ?: 'has-error' !!}">

        @if ($errors->has('mobile'))
            @foreach ($errors->get('mobile') as $message)
                <label class="control-label" for="inputError">
                    <i class="fa-regular fa-times-circle"></i> {{ $message }}
                </label><br>
            @endforeach
        @endif
    </div>

    <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">
        @if ($errors->has('password'))
            @foreach ($errors->get('password') as $message)
                <label class="control-label" for="inputError">
                    <i class="fa-regular fa-times-circle"></i> {{ $message }}
                </label><br>
            @endforeach
        @endif
    </div>

    <input type="hidden" name="mobile" value="{{ session('mobile') }}">

    <label for="field-password">{{ trans('admin.sms-token') }}</label>
    <div class="relative">
        <input type="text" class="form-control" placeholder="{{ trans('admin.sms-token-placeholder') }}"
            name="password" value="{{ old('password') }}" id="field-password">
        <span class="fa-regular fa-key form-control-feedback"></span>
    </div>
    <p class="help-block">{{ trans('admin.sms-token-hint') }}</p>
    </div>
    <div class="row">
        <div class="col-xs-4 col-xs-offset-8">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary btn-block">{{ trans('admin.login') }}</button>
        </div>
        <!-- /.col -->
    </div>
</form>
