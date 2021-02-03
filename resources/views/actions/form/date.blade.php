<div class="form-group">
    <label>{{ $label }}</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="far fa-calendar fa-w"></i>
            </span>
        </div>
        <input {!! $attributes !!} />
    </div>
    @include('admin::actions.form.help-block')
</div>

<script require="datetimepicker" selector="{{ $selector }}" all="true">
    $(this).datetimepicker(@json($options));
</script>
