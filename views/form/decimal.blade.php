<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-terminal"></i>
            </div>
            <input type="text" id="{{$id}}" name="{{$name}}" value="{{ old($column, $value) }}"  class="form-control" placeholder="{{ trans('admin::lang.input') }} {{$label}}" style="width: 130px" {!! $attributes !!} />
        </div>

        @include('admin::form.help-block')

    </div>
</div>