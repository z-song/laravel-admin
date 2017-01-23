<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 col-lg-2 control-label">{{$label}}</label>

    <div class="col-sm-10 col-lg-8">

        @include('admin::form.error')

        <input type="checkbox" class="{{$class}}_checkbox" {{ old($column, $value) == 'on' ? 'checked' : '' }} {!! $attributes !!} />
        <input type="hidden" class="{{$class}}" name="{{$name}}" class="" value="{{ old($column, $value) }}" />

        @include('admin::form.help-block')

    </div>
</div>
