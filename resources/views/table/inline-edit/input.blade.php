@extends('admin::table.inline-edit.comm', ['type' => 'input'])

@section('field')
    <input class="form-control ie-input"/>
@endsection

@section('assert')
    <script>
        @component('admin::table.inline-edit.partials.popover', compact('trigger'))
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
    @include('admin::table.inline-edit.partials.submit', compact('resource', 'name'))
@endsection
