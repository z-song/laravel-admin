<div class="form-group">
    <label>{{ $label }}</label>
    <div class="input-group">
        @if ($prepend)
            <div class="input-group-prepend">
                {!! $prepend !!}
            </div>
        @endif
    <input {!! $attributes !!}>
    </div>
    @include('admin::actions.form.help-block')
</div>

@if($inputmask)
    <script require="inputmask" selector="{{ $selector }}" all="true">
        $(this).inputmask({!! json_encode_options($inputmask)  !!});
    </script>
@endif
