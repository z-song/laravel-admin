<div class="btn-group">
    <button type="button" class="btn btn-xs btn-@color table-orderable" data-id="{{ $key }}" data-direction="1">
        <i class="fa fa-caret-up fa-fw"></i>
    </button>
    <button type="button" class="btn btn-xs btn-default table-orderable" data-id="{{ $key }}" data-direction="0">
        <i class="fa fa-caret-down fa-fw"></i>
    </button>
</div>

<script>
    $('.table-orderable').on('click', function () {

        var key = $(this).data('id');
        var direction = $(this).data('direction');

        $.put({
            url:'{!! $resource !!}/' + key,
            data: {_orderable: direction}
        }).done(function (data) {
            if (data.status) {
                $.admin.reload(data.message);
            } else {
                $.admin.toastr.warning(data.message);
            }
        });
    });
</script>
