@extends('admin::grid.inline-edit.comm')

<span class="ie-wrap">
    <span class="ie-display">{{ $options[$value] }}</span>
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
        @foreach($options as $option => $label)
            <div class="radio icheck">
                <label>
                    <input type="radio" name='radio-{{ $name }}' class="minimal ie-input" value="{{ $option }}" data-label="{{ $label }}"/>&nbsp;{{$label}}&nbsp;&nbsp;
                </label>
            </div>
        @endforeach
    @endcomponent
</span>

<style>
    tbody tr:hover .{{ $trigger }}  {
        visibility: visible !important;
    }

    .icheck.radio {
        margin: 5px 0 5px 20px;
    }

    .ie-content-{{ $name }} .ie-container  {
        width: 150px;
        position: relative;
    }
</style>

<script>
    @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
        @slot('popover')
        $popover.find('input[type=radio]').each(function (index, radio) {
            if ($(radio).attr('value') == $trigger.data('value')) {
                $(radio).prop('checked', true);
            }
        });
        @endslot
    @endcomponent
</script>

<script>
@component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))

    @slot('val')
        var val = $popover.find('.ie-input:checked').val();
        var label = $popover.find('.ie-input:checked').data('label');
    @endslot

    $popover.data('display').html(label);

@endcomponent
</script>

