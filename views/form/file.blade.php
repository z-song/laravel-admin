<div class="form-group {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')
        <input type="file" class="{{$class}}" name="{{$name}}{{$multiple ? '[]' : ''}}" {!! $attributes !!} />
        <input type="hidden" class="{{$class}}_action" name="{{$name}}_action" value="0"/>

        @include('admin::form.help-block')

    </div>
</div>
