<div class="{{$viewClass['form-group']}}">
    <label for="{{$id['start']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="row" style="width: 530px;">
            <div class="col-sm-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far {{ $icon }} fa-fw"></i>
                        </span>
                    </div>
                    <input type="text" name="{{$name['start']}}" value="{{ old($column['start'], $value['start'] ?? null) }}" class="form-control {{$class['start']}}" {!! $attributes !!} />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far {{ $icon }} fa-fw"></i>
                        </span>
                    </div>
                    <input type="text" name="{{$name['end']}}" value="{{ old($column['end'], $value['end'] ?? null) }}" class="form-control {{$class['end']}}" {!! $attributes !!} />
                </div>
            </div>
        </div>
        @include('admin::form.help-block')
    </div>
</div>

<script require="datetimepicker">
    var $start = $('.{{ $class['start'] }}');
    var $end = $('.{{ $class['end'] }}');

    $start.datetimepicker(@json($start_options))
        .on('dp.change', function (e) {
            $end.data('DateTimePicker').minDate(e.date);
        });

    $end.datetimepicker(@json($end_options))
        .on('dp.change', function (e) {
            $start.data('DateTimePicker').maxDate(e.date);
        });
</script>
