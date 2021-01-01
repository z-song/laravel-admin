@extends('admin::table.inline-edit.comm', ['type' => 'textarea'])

@section('field')
    <textarea class="form-control ie-input" rows="{{ $rows }}"></textarea>
@endsection

@section('assert')
    <script>
        @component('admin::table.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
                $template.find('textarea').text($trigger.data('value'));
            @endslot
            @slot('shown')
                $popover.find('.ie-input').focus();
            @endslot
        @endcomponent
    </script>

    {{--after submit--}}
    @include('admin::table.inline-edit.partials.submit', compact('resource', 'name'))
@endsection
