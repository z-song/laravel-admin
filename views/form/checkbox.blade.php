<div class="form-group {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6" id="{{$id}}">

        @include('admin::form.error')

        <div class="input-group">
            @foreach($options as $option => $label)
            <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="{{$id}}" {{ in_array($option, (array)old($column, $value))?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
            @endforeach
        </div>

        <input type="hidden" name="{{$name}}[]">

        @include('admin::form.help-block')

    </div>
</div>
