<div class="icheck-@theme d-inline">
    <input type="checkbox" class="{{ $row }}-checkbox" data-id="{{ $key }}" id="@id-{{ $key }}">
    <label for="@id-{{ $key }}"></label>
</div>

<script require="icheck">
    $('.{{ $row }}-checkbox').on('change', function () {
        $.admin.table.toggle($(this).data('id'));
    });
</script>
