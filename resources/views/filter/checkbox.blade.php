<div class="input-group">
    @foreach($options as $option => $label)
        @if(!$inline)<div class="radio">@endif
            <label @if($inline)class="radio-inline"@endif>
                <input type="checkbox" class="{{$id}}" name="{{$name}}[]" value="{{$option}}" class="minimal" {{ in_array((string)$option, request($name, is_null($value) ? [] : $value)) ? 'checked' : '' }} />&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
            @if(!$inline)</div>@endif
    @endforeach
</div>