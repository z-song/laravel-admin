@admin_require('icheck')

<input type="checkbox" class="{{ $row }}-checkbox" data-id="{{ $key }}" />

<script>
    $('.{{ $row }}-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {

        var id = $(this).data('id');

        if (this.checked) {
            $.admin.grid.select(id);
            $(this).closest('tr').css('background-color', '#ffffd5');
        } else {
            $.admin.grid.unselect(id);
            $(this).closest('tr').css('background-color', '');
        }
    }).on('ifClicked', function () {

        var id = $(this).data('id');

        if (this.checked) {
            $.admin.grid.unselect(id);
        } else {
            $.admin.grid.select(id);
        }

        var selected = $.admin.grid.selected().length;

        if (selected > 0) {
            $('.{{ $all }}-btn').show();
        } else {
            $('.{{ $all }}-btn').hide();
        }

        $('.{{ $all }}-btn .selected').html("{{ trans('admin.grid_items_selected') }}".replace('{n}', selected));
    });
</script>
