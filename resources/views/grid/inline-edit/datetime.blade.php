@extends('admin::grid.inline-edit.comm')

@section('field')
    <input class="form-control ie-input" value="{__VAL__}"/>
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
        @component('admin::grid.inline-edit.partials..popover', compact('trigger'))
            // open popover
            @slot('popover')
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

            // popover content
            @slot('content')
                $(this)
                    .parents('.ie-wrap')
                    .find('template')
                    .html()
                    .replace('{__VAL__}', $(this).data('value'));
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


