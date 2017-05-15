<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

  <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

  <div
    data-block="field-switch"
    data-options-field-switch='{!! $dataSet !!}'
    class="col-sm-{{$width['field']}}">

    @include('admin::form.error')

    <input
      data-element="field-switch-input"
      type="checkbox" class="{{$class}}"
      {{ old($column, $value) == 'on' ? 'checked' : '' }} {!! $attributes !!}>
    <input
      data-element="field-switch-keeper"
      type="hidden" class="{{$class}}"
      name="{{$name}}" class="" value="{{ old($column, $value) }}">

    &nbsp;

    <button
      data-element="field-switch-unset"
      type="button"
      class="{{ $class }} btn btn-default btn-sm">
      <span class="glyphicon glyphicon-remove text-muted"
            data-switch-toggle="disabled"></span>
    </button>

    @include('admin::form.help-block')

  </div>
</div>
