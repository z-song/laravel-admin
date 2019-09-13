<div class="form-group">
    <label>{{ $label }}</label>
    <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}" {!! $attributes !!} multiple data-placeholder="{{ $label }}">

        <option value=""></option>
        @foreach($options as $select => $option)
            <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
        @endforeach
    </select>
</div>

