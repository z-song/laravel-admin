<div class="form-group {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <input type="text" id="{{$id}}" name="{{$name}}" data-from="{{ old($column, $value) }}" {!! $attributes !!} />

        @include('admin::form.help-block')

    </div>
</div>
