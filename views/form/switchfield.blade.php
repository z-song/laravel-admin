<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <input type="checkbox" id="{{$id}}_checkbox" {{ $value == 'on' ? 'checked' : '' }} {!! $attributes !!} />
        <input type="hidden" id="{{$id}}" name="{{$name}}" class="" value="{{ old($column, $value) }}" />

    </div>
</div>