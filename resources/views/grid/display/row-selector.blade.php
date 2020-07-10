<input type="checkbox" class="{{ $row }}-checkbox" data-id="{{ $key }}" />

<script require="icheck">
    var $table = {!!  $__table  !!};
    $.admin.initTable($table);
    $('.{{ $row }}-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'})
        .on('ifChanged', function () {
            $table.toggle($(this).data('id'));
        });
</script>
