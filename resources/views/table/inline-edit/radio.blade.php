@extends('admin::table.inline-edit.comm', ['type' => 'radio'])

@section('field')
    @foreach($options as $option => $label)
        <div class="radio icheck-@color">
            <input id="@id" type="radio" name='radio-{{ $name }}' class="minimal ie-input" value="{{ $option }}" data-label="{{ $label }}"/>
            <label for="@id">&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        </div>
    @endforeach
@endsection

@section('assert')
    <style>
        .icheck-@color.radio {
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
                $template.find('input[type=radio]').each(function (index, radio) {
                    if ($(radio).attr('value') == $trigger.data('value')) {
                        $(radio).attr('checked', true);
                    }
                });
            @endslot
        @endcomponent
    </script>

    @include('admin::table.inline-edit.partials.submit', compact('resource', 'name'))
@endsection
