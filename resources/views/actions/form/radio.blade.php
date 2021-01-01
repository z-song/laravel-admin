<div class="form-group">
    <label>{{ $label }}</label>
    <div>
    @foreach($options as $option => $label)
        <span class="icheck-@color">
            <input id="@id" type="radio" name="{{$name}}" value="{{$option}}" class="minimal {{$class}}" {{ ($option == $value) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />
            <label for="@id" class="radio-inline">&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        </span>
    @endforeach
    </div>
    @include('admin::actions.form.help-block')
</div>
