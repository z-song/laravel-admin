@extends('admin::grid.inline-edit.comm')

@section('field')
    <textarea class="form-control ie-input" rows="{{ $rows }}"></textarea>
@endsection

@section('assert')
    <script>
        @component('admin::grid.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
                $template.find('textarea').text($trigger.data('value'));
            @endslot
            @slot('shown')
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


