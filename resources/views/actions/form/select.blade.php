<div class="form-group">
    <label>{{ $label }}</label>

    <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}" {!! $attributes !!} >

        <option value=""></option>
        @foreach($options as $select => $option)
            <option value="{{$select}}" {{ $select == $value ?'selected':'' }}>{{$option}}</option>
        @endforeach
    </select>
    @include('admin::actions.form.help-block')
</div>

<script require="select2" selector="{{ $selector }}" all="true">
    $(this).select2(@json($configs));
</script>
