<div class="{{ $viewClass['form-group'] }} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{ $id }}" class="{{ $viewClass['label'] }} control-label">{{ $label }}</label>

    <div class="{{ $viewClass['field'] }}">

        @include('admin::form.error')

        <div class="input-group">

            @if ($prepend)
                <span class="input-group-addon">{!! $prepend !!}</span>
            @endif

            <input {!! $attributes !!} />

            @if ($append)
                <span class="input-group-addon clearfix">{!! $append !!}</span>
            @endif

            @isset($btn)
                <span class="input-group-btn">
                    {!! $btn !!}
                </span>
            @endisset

        </div>

        @if($number2persian ?? false)
            <span class="help-block">
                <span id="number2persian-box-{{ $id }}"></span>
            </span>
        @endif

        @if($number2commaSeparated ?? false)
            <span class="help-block">
                <span id="number2comma-separated-box-{{ $id }}"></span>
            </span>
        @endif

        @include('admin::form.help-block')

    </div>
</div>
