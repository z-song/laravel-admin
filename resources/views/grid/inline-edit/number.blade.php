@extends('admin::grid.inline-edit.comm')

@section('field')
    <input type="number" class="form-control ie-input" />
    @if ($number2persian)
        <span class="help-block">
            <span class="number2persian-box"></span>
        </span>
    @endif
@endsection

@section('assert')
    <script>
        @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
                $template.find('input').attr('value', $trigger.data('value'));
            @endslot
            @slot('shown')
                $popover.find('.ie-input').focus();
                @if (!$number2persian)
                    let number2persianBox = $popover.find('.number2persian-box');
                    $popover.find('.ie-input').on('keyup', function() {
                        number2persianBox.text(parseInt($trigger.data('value')).num2persian());
                    });
                @endif
            @endslot
        @endcomponent
    </script>

    {{-- after submit --}}
    <script>
        @component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))
            $popover.data('display').html(val.toLocaleString('en-US'));
        @endcomponent
    </script>
@endsection
