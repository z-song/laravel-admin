<div class="icheck-primary d-inline">
    <input type="checkbox" class="{{ $row }}-checkbox" data-id="{{ $key }}" id="row-id-{{ $key }}">
    <label for="row-id-{{ $key }}"></label>
</div>

{{--<input type="checkbox" class="{{ $row }}-checkbox" data-id="{{ $key }}" />--}}

<script require="icheck">
    $('.{{ $row }}-checkbox').on('change', function () {
        $.admin.table.toggle($(this).data('id'));
    });
</script>
