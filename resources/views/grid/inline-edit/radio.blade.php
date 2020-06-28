@extends('admin::grid.inline-edit.comm')

@section('field')
    @foreach($options as $option => $label)
        <div class="radio icheck">
            <label>
                <input type="radio" name='radio-{{ $name }}' class="minimal ie-input" value="{{ $option }}" data-label="{{ $label }}"/>&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        </div>
    @endforeach
@endsection

@section('assert')
    <style>
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
            @slot('content')
                $template.find('input[type=radio]').each(function (index, radio) {
                    if ($(radio).attr('value') == $trigger.data('value')) {
                        $(radio).attr('checked', true);
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
@endsection

