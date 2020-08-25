<div class="input-group">
    @foreach($options as $option => $label)
        {!! $inline ? admin_color('<span class="icheck-%s">') : admin_color('<div class="radio icheck-%s">')  !!}
            <input
                id="@id"
                type="radio"
                class="{{$id}}"
                name="{{$name}}"
                value="{{$option}}"
                class="minimal"
                {{ ((string)$option === request($name, is_null($value) ? '' : $value)) ? 'checked' : '' }}
            />
            <label for="@id">
                &nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        {!! $inline ? '</span>' :  '</div>' !!}
    @endforeach
</div>
