@extends('admin::table.inline-edit.comm')

@section('field')
    @foreach($options as $option => $label)
        <div class="checkbox icheck-@theme">
            <input id="@id" type="checkbox" name='radio-{{ $name }}[]' class="minimal ie-input" value="{{ $option }}" data-label="{{ $label }}"/>
            <label for="@id">&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        </div>
    @endforeach
@endsection

@section('assert')
    <style>
        .icheck-@theme.checkbox {
            margin: 5px 0 5px 20px;
        }

        .ie-content-{{ $name }} .ie-container  {
            width: 150px;
            position: relative;
        }
    </style>

    <script>
        @component('admin::table.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
            $template.find('input[type=checkbox]').each(function (index, checkbox) {
                if($.inArray($(checkbox).attr('value'), $trigger.data('value')) >= 0) {
                    $(checkbox).attr('checked', true);
                }
            });
            @endslot
        @endcomponent
    </script>

    <script>
    @component('admin::table.inline-edit.partials.submit', compact('resource', 'name'))

        @slot('val')
            var val = [];
            var label = [];
            $popover.find('.ie-input:checked').each(function(){
                val.push($(this).val());
                label.push($(this).data('label'));
            });
        @endslot

        $popover.data('display').html(label.join(';'));

    @endcomponent
    </script>
@endsection
