@extends('admin::grid.inline-edit.comm')

@section('field')
    @foreach($options as $option => $label)
        <div class="checkbox icheck">
            <label>
                <input type="checkbox" name='radio-{{ $name }}[]' class="minimal ie-input" value="{{ $option }}" data-label="{{ $label }}"/>&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        </div>
    @endforeach
@endsection

@section('assert')
    <style>
        .icheck.checkbox {
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
            $popover.find('input[type=checkbox]').each(function (index, checkbox) {
                if($.inArray($(checkbox).attr('value'), $trigger.data('value')) >= 0) {
                    $(checkbox).prop('checked', true);
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
            $popover.find('.ie-input:checked').each(function(){
                val.push($(this).val());
                label.push($(this).data('label'));
            });
            console.log(val, label)
        @endslot

        $popover.data('display').html(label.join(';'));

    @endcomponent
    </script>
@endsection

