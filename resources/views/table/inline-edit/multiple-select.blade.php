@extends('admin::table.inline-edit.comm', ['type' => 'multiple-select'])

@section('field')
    <select name='multiple-select-{{ $name }}' class="form-control ie-input" multiple>
        @foreach($options as $option => $label)
            <option name='multiple-select-{{ $name }}' value="{{ $option }}" data-label="{{ $label }}">&nbsp;{{$label}}&nbsp;&nbsp;</option>
        @endforeach
    </select>
@endsection

@section('assert')
    <script>
        @component('admin::table.inline-edit.partials.popover', compact('trigger'))
            @slot('content')
            $template.find('select>option').each(function (index, option) {
                if($.inArray($(option).attr('value'), $trigger.data('value')) >= 0) {
                    $(option).attr('selected', true);
                }
            });
            @endslot
        @endcomponent
    </script>

    @include('admin::table.inline-edit.partials.submit', compact('resource', 'name'))
@endsection
