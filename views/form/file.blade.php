<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')
        <input type="file" id="{{$id}}" name="{{$name}}" {!! $attributes !!} />
        <input type="hidden" id="{{$id}}_action" name="{{$name}}_action" value="0"/>

        @include('admin::form.help-block')

    </div>
</div>