@extends('admin::grid.inline-edit.comm')

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

    <script>
        @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
            $template.find('input').attr('value', $trigger.data('value'));
            @endslot
            @slot('shown')
            var $input  = $popover.find('.ie-input');

            $popover.find('.ie-container').datetimepicker({
                inline: true,
                format: '{{ $format }}',
                date: $input.val(),
                locale: '{{ $locale }}'
            }).on('dp.change', function (event) {
                var date = event.date.format('{{ $format }}');
                $input.val(date);
            });
            @endslot
        @endcomponent
    </script>

    {{--after submit--}}
    <script>
    @component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))
        $popover.data('display').html(val);
    @endcomponent
    </script>

@endsection


