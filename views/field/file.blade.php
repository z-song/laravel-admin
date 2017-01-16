<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::field.error')
        <input type="file" class="{{$class}}" name="{{$name}}{{$multiple ? '[]' : ''}}" {!! $attributes !!} />
        <input type="hidden" class="{{$class}}_action" name="{{$name}}_action" value="0"/>

        @include('admin::field.help-block')

    </div>
</div>
