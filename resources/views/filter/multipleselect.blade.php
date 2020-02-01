<select class="form-control {{ $class }}" name="{{$name}}[]" multiple style="width: 100%;">
    <option></option>
    @php
        $selected = request($name, []);
        $selected = is_array($selected) ? $selected : [];
    @endphp

    @foreach($options as $select => $option)
        <option value="{{$select}}" {{ in_array((string)$select, $selected) ? 'selected' : '' }}>{{$option}}</option>
    @endforeach
</select>