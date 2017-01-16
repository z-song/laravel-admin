<div class="form-group {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6" id="{{$id}}">

        @include('admin::field.error')

        <div class="checkbox">
            @foreach($options as $option => $label)
            <label>
                <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="{{$class}}" {{ in_array($option, (array)old($column, $value))?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
            @endforeach
        </div>

        <input type="hidden" name="{{$name}}[]">

        @include('admin::field.help-block')

    </div>
</div>
