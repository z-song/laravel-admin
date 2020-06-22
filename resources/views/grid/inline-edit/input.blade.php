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
        <input class="form-control ie-input" value="{__VAL__}"/>
    @endcomponent
</span>

<style>
    tbody tr:hover .{{ $trigger }}  {
        visibility: visible !important;
    }
</style>

<script>
    @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
        @slot('content')
        $(this)
            .parents('.ie-wrap')
            .find('template')
            .html()
            .replace('{__VAL__}', $(this).data('value'));
        @endslot

        @slot('popover')
            @if($mask)
            $popover.find('.ie-input').inputmask(@json($mask));
            @endif
        @endslot
    @endcomponent
</script>

{{--after submit--}}
<script>
@component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))
    $popover.data('display').html(val);
@endcomponent
</script>


