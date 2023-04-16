<form action="{{ admin_url('auth/req-otp') }}" method="post">
    <div class="form-group has-feedback {!! !$errors->has('mobile') ?: 'has-error' !!}">

        @if ($errors->has('mobile'))
            @foreach ($errors->get('mobile') as $message)
                <label class="control-label" for="inputError">
                    <i class="fa-regular fa-times-circle"></i> {{ $message }}
                </label><br>
            @endforeach
        @endif

        <label for="field-mobile">{{ trans('admin.mobile') }}</label>
        <div class="relative">
            <input type="text" class="form-control" placeholder="{{ trans('admin.sample-mobile-number') }}"
                name="mobile" value="{{ old('mobile') }}" id="field-mobile">
            <span class="fa-regular fa-phone form-control-feedback"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 col-xs-offset-8">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary btn-block">{{ trans('admin.send-otp') }}</button>
        </div>
        <!-- /.col -->
    </div>
</form>
