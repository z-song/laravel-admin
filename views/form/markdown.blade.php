<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-10">

        @include('admin::form.error')

        <textarea id="{{$id}}" name="{{$name}}" class="form-control" data-provide="markdown" >{{ old($column, $value) }}</textarea>
    </div>
</div>