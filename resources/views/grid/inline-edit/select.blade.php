@extends('admin::grid.inline-edit.comm')

@section('field')
    <select name='select-{{ $name }}' class="form-control ie-input">
    @foreach($options as $option => $label)
        <option name='select-{{ $name }}' value="{{ $option }}" data-label="{{ $label }}">&nbsp;{{$label}}&nbsp;&nbsp;</option>
    @endforeach
    </select>
@endsection

@section('assert')
    <script>
        @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
            $template.find('select>option').each(function (index, option) {
                if ($(option).attr('value') == $trigger.data('value')) {
                    $(option).attr('selected', true);
                }
            });
            @endslot
        @endcomponent
    </script>

    <script>
    @component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))

        @slot('val')
            var val = $popover.find('.ie-input').val();
            var label = $popover.find('.ie-input>option:selected').data('label');
        @endslot

        $popover.data('display').html(label);

    @endcomponent
    </script>
@endsection

