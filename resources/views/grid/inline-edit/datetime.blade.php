@extends('admin::grid.inline-edit.comm')

<span class="ie-wrap">
    <a
        href="javascript:void(0);"
        class="{{ $trigger }}"
        data-toggle="popover"
        data-target="{{ $target }}"
        data-value="{{ $value }}"
        data-original="{{ $value }}"
    >
        <i class="fa fa-clock-o"></i>
        <span class="ie-display">{{ $value }}</span>
    </a>
    @component('admin::grid.inline-edit.partials.template', compact('name', 'target', 'key'))
        <input class="form-control ie-input" value="{__VAL__}"/>
    @endcomponent
</span>

<style>
    .{{ $trigger }} {
        padding: 3px;
        border-radius: 3px;
        color:#777;
    }

    .{{ $trigger }}:hover {
        text-decoration: none;
        background-color: #ddd;
        color:#777;
    }

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


