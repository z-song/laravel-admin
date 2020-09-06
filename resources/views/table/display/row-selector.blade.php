<div class="icheck-@color d-inline">
    <input type="checkbox" class="table-row-checkbox" data-id="{{ $key }}" id="@id-{{ $key }}">
    <label for="@id-{{ $key }}"></label>
</div>

<script require="icheck">
    $('.table-row-checkbox').on('change', function () {
        $.admin.table.toggle($(this).data('id'));
    });
</script>
