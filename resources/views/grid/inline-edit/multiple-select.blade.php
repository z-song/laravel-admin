@extends('admin::grid.inline-edit.comm')

@section('field')
    <select name='multiple-select-{{ $name }}' class="form-control ie-input" multiple>
        @foreach($options as $option => $label)
            <option name='multiple-select-{{ $name }}' value="{{ $option }}" data-label="{{ $label }}">&nbsp;{{$label}}&nbsp;&nbsp;</option>
        @endforeach
    </select>
@endsection

@section('assert')
    <script>
        @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
            @slot('popover')
            $popover.find('select>option').each(function (index, option) {
                if($.inArray($(option).attr('value'), $trigger.data('value')) >= 0) {
                    $(option).prop('selected', true);
                }
            });
            @endslot
        @endcomponent
    </script>

    <script>
    @component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))

        @slot('val')
            var val = [];
            var label = [];
            $popover.find('.ie-input>option:selected').each(function(){
                val.push($(this).val());
                label.push($(this).data('label'));
            });
            console.log(val, label)
        @endslot

        $popover.data('display').html(label.join(';'));

    @endcomponent
    </script>
@endsection

