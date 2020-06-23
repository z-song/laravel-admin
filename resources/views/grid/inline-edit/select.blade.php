@extends('admin::grid.inline-edit.comm')

<span class="ie-wrap">
    <span class="ie-display">{{ $value }}</span>
    &nbsp;
    <a
        href="javascript:void(0);"
        class="{{ $trigger }} text-muted"
        style="visibility: hidden;"
        data-target="{{ $target }}"
        data-toggle="popover"
        data-value="{{ $value }}"
        data-original="{{ $value }}"
    >
        <i class="fa fa-edit"></i>
    </a>

    @component('admin::grid.inline-edit.partials.template', compact('name', 'target', 'key'))
        <select name='radio-{{ $name }}' class="form-control ie-input" style="z-index: 1070;">
        @foreach($options as $option => $label)
            <option name='radio-{{ $name }}' value="{{ $option }}" data-label="{{ $label }}">&nbsp;{{$label}}&nbsp;&nbsp;</option>
        @endforeach
        </select>
    @endcomponent
</span>

<style>
    tbody tr:hover .{{ $trigger }}  {
        visibility: visible !important;
    }
</style>

<script>
    @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
        @slot('popover')
        $popover.find('select>option').each(function (index, option) {
            if ($(option).attr('value') == $trigger.data('value')) {
                $(option).prop('selected', true);
            }
        });
        $popover.find('.ie-input').select2();
        @endslot
    @endcomponent
</script>

<script>
@component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))

    @slot('val')
        var val = $popover.find('.ie-input').val();
        var label = $popover.find('.ie-input:selected').data('label');
    @endslot

    $popover.data('display').html(label);

@endcomponent
</script>

