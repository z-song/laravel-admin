<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::field.error')

        <div class="input-group {{$class}}">
            <span class="input-group-addon"><i></i></span>
            <input type="text" name="{{$name}}" value="{{ old($column, $value) }}" class="form-control" placeholder="{{ $placeholder }}" {!! $attributes !!}  style="width: 140px" />
        </div>

        @include('admin::field.help-block')

    </div>
</div>
