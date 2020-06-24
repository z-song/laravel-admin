@extends('admin::grid.inline-edit.comm')

@section('field')
    <textarea class="form-control ie-input" rows="{{ $rows }}">{__VAL__}</textarea>
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


