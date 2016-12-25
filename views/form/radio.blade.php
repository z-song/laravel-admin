<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        @foreach($options as $option => $label)
            <div class="radio">
                <label>
                    <input type="radio" name="{{$name}}" value="{{$option}}" class="minimal {{$class}}" {{ ($option == old($column, $value))?'checked':'' }} />&nbsp;{{$label}}&nbsp;&nbsp;
                </label>
            </div>
        @endforeach

        @include('admin::form.help-block')

    </div>
</div>
