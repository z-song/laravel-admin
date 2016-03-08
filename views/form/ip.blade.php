<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-laptop"></i>
            </div>
            <input type="text" id="{{$id}}" name="{{$name}}" value="{{ old($column, $value) }}"  class="form-control" data-inputmask="'alias': 'ip'" data-mask placeholder="{{ Lang::get('admin::lang.input') }} {{$label}}">
        </div>
    </div>
</div>