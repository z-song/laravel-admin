<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

  <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

  <div class="col-sm-{{$width['field']}}">

    @include('admin::form.error')
    <div
      data-block="field-select"
      data-options-field-select='{!! $dataSet !!}'>
      <select
        data-element="field-select-input"
        class="form-control {{$class}}" style="width: 100%;" name="{{$name}}" {!! $attributes !!}>
        @foreach($options as $select => $option)
          <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
        @endforeach
      </select>

      <input
        data-element="field-select-keeper"
        type="hidden" name="{{$name}}"/>
    </div>


    @include('admin::form.help-block')

  </div>
</div>
