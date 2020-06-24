@extends('admin::grid.inline-edit.comm')

@section('field')
    <input class="form-control ie-input" value="{__VAL__}"/>
@endsection

@section('assert')
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
                $popover.find('.ie-input').focus();
                @if($mask)
                $popover.find('.ie-input').inputmask(@json($mask));
                @endif
            @endslot
        @endcomponent
    </script>

    {{--after submit--}}
    <script>
    @component('admin::grid.inline-edit.partials.submit', compact('resource', 'name', 'target'))
        $popover.data('display').html(val);
    @endcomponent
    </script>
@endsection


