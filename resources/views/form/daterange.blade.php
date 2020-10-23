<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id['start']}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="row">
            <div class="col field-control">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far {{ $icon }} fa-fw"></i>
                        </span>
                    </div>
                    <input
                        type="text"
                        name="{{$name['start']}}"
                        value="{{ $value['start'] ?? null }}"
                        class="form-control {{$class['start']}}"
                        {!! $attributes !!}/>
                </div>
                <div class="col d-none validation-error {{ $column['start'] }}-error">
                    <label class="col-form-label text-danger">
                        <i class="fas fa-bell"></i>
                    </label>
                </div>
            </div>
            <div class="col field-control">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far {{ $icon }} fa-fw"></i>
                        </span>
                    </div>
                    <input
                        type="text"
                        name="{{$name['end']}}"
                        value="{{ $value['end'] ?? null }}"
                        class="form-control {{$class['end']}}"
                        {!! $attributes !!}/>
                </div>
                <div class="col d-none validation-error {{ $column['end'] }}-error">
                    <label class="col-form-label text-danger">
                        <i class="fas fa-bell"></i>
                    </label>
                </div>
            </div>
        </div>
        @include('admin::form.help-block')
    </div>
</div>

<script require="datetimepicker" selector="{{ $selector['start'] }}" nested="{{ $nested }}">
    var $end = $(this).closest('.row').find('{{ $selector['end']}}');
    $(this).datetimepicker(@json($start_options))
        .on('dp.change', function (e) {
            $end.data('DateTimePicker').minDate(e.date);
        });

    $end.datetimepicker(@json($end_options))
        .on('dp.change', function (e) {
            $(this).data('DateTimePicker').maxDate(e.date);
        });

    $(this).addClass('initialized');
</script>
