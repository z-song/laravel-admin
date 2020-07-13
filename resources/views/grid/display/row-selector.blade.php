<input type="checkbox" class="{{ $row }}-checkbox" data-id="{{ $key }}" />

<script require="icheck">
    $('.{{ $row }}-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'})
        .on('ifChanged', function () {
            $.admin.table.toggle($(this).data('id'));
        });
</script>
