<select class="form-control " style="width: 100%;" name="{{$name}}">
        <option value=" "></option>
    @foreach($options as $select => $option)
        <option value="{{$select}}" {{ $select == request($name, $value) ?'selected':'' }}>{{$option}}</option>
    @endforeach
</select>
