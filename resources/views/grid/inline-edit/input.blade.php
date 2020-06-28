@extends('admin::grid.inline-edit.comm')

@section('field')
    <input class="form-control ie-input"/>
@endsection

@section('assert')
    <script>
        @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
            $template.find('input').attr('value', $trigger.data('value'));
            @endslot
            @slot('shown')
                $popover.find('.ie-input').focus();
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
@endsection


