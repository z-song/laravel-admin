<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id['start']}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <div class="row" style="width: 370px">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" id="{{$id['start']}}" name="{{$name['start']}}" value="{{ old($column['start'], $value['start']) }}" class="form-control" style="width: 150px" {!! $attributes !!} />
                </div>
            </div>

            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" id="{{$id['end']}}" name="{{$name['end']}}" value="{{ old($column['end'], $value['end']) }}" class="form-control" style="width: 150px" {!! $attributes !!} />
                </div>
            </div>
        </div>

        @include('admin::form.help-block')

    </div>
</div>