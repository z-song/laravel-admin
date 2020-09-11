@extends('admin::table.inline-edit.comm', ['type' => 'datetime'])

@section('field')
    <input class="form-control ie-input"/>
@endsection

@section('assert')
    <style>
        .ie-content-{{ $name }} .ie-container  {
            height: 290px;
        }

        .ie-content-{{ $name }} .ie-input {
            display: none;
        }
    </style>

    <script require="datetimepicker">
        @component('admin::table.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
            $template.find('input').attr('value', $trigger.data('value'));
            @endslot
            @slot('shown')
            var $input  = $popover.find('.ie-input');
    $popover.find('.ie-container').datetimepicker({
        inline: true,
        format: '{{ $format }}',
        date: $input.val(),
        locale: '{{ $locale }}',
        icons: {
            time: 'fas fa-clock'
        }
    }).on('dp.change', function (event) {
        var date = event.date.format('{{ $format }}');
        $input.val(date);
    });
            @endslot
        @endcomponent
    </script>

    {{--after submit--}}
    @include('admin::table.inline-edit.partials.submit', compact('resource', 'name'))

@endsection
