<select class="form-control {{ $class }}" style="width: 100%;" name="{{$name}}">
    @foreach($options as $select => $option)
        <option value="{{$select}}" {{ (string)$select === request($name, $value) ?'selected':'' }}>{{$option}}</option>
    @endforeach
</select>