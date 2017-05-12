<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

    <div class="col-sm-{{$width['field']}}">

        @include('admin::form.error')

        <input type="checkbox" class="{{$class}} la_checkbox" {{ old($column, $value) == 'on' ? 'checked' : '' }} {!! $attributes !!}>
        <input type="hidden" class="{{$class}}" name="{{$name}}" class="" value="{{ old($column, $value) }}">

        &nbsp;

        <button type="button" class="{{ $class }} la_checkbox_unset btn btn-default btn-sm">
          <span class="glyphicon glyphicon-remove text-muted"
                data-switch-toggle="disabled"></span>
        </button>

        @include('admin::form.help-block')

    </div>
</div>
