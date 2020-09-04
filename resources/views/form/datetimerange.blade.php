<div class="{{$viewClass['form-group']}} {!! ($errors->has($errorKey['start']) || $errors->has($errorKey['end'])) ? 'has-error' : ''  !!}">

    <label for="{{$id['start']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <div class="row">
            <div class="col-lg-12">
                @if($errors->has($errorKey['start']))
                    @foreach($errors->get($errorKey['start']) as $message)
                        <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                    @endforeach
                @endif

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text"
                           name="{{$name['start']}}"
                           value="{{ old($column['start'], $value['start'] ?? null) }}"
                           class="form-control {{$class['start']}}"
                           style="width: 160px"
                            {!! $attributes !!}
                    />
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 5px">
            <div class="col-lg-12">
                @if($errors->has($errorKey['end']))
                    @foreach($errors->get($errorKey['end']) as $message)
                        <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                    @endforeach
                @endif

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text"
                           name="{{$name['end']}}"
                           value="{{ old($column['end'], $value['end'] ?? null) }}"
                           class="form-control {{$class['end']}}"
                           style="width: 160px"
                            {!! $attributes !!}
                    />
                </div>
            </div>
        </div>

        @include('admin::form.help-block')

    </div>
</div>
