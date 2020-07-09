<input type="checkbox" class="{{ $row }}-checkbox" data-id="{{ $key }}" />

<script require="icheck">
    var $table = {!!  $__table  !!};
    $.admin.initTable($table);

    $('.{{ $row }}-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {

        var id = $(this).data('id');

        if (this.checked) {
            $table.select(id);
            $(this).closest('tr').css('background-color', '#ffffd5');
        } else {
            $table.unselect(id);
            $(this).closest('tr').css('background-color', '');
        }

    }).on('ifClicked', function () {

        var id = $(this).data('id');

        if (this.checked) {
            $table.unselect(id);
        } else {
            $table.select(id);
        }

        var selected = $table.selected().length;

        if (selected > 0) {
            $('.{{ $all }}-btn').show();
        } else {
            $('.{{ $all }}-btn').hide();
        }

        $('.{{ $all }}-btn .selected').html("{{ trans('admin.grid_items_selected') }}".replace('{n}', selected));
    });
</script>
