<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

    <div class="col-sm-{{$width['field']}}">

        @include('admin::form.error')
        <input type="file" class="{{$class}}" name="{{$name}}{{$multiple ? '[]' : ''}}" {!! $attributes !!}/>
        <input type="hidden" class="{{$class}}_action" name="{{$actionName}}" value="0"/>

        @include('admin::form.help-block')

    </div>
</div>
