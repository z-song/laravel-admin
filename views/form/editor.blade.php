<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-10">

        @include('admin::form.error')

        <textarea class="form-control" id="{{$id}}" name="{{$name}}" placeholder="输入{{$label}}">{{ old($column, $value) }}</textarea>
    </div>
</div>