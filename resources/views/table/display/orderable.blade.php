<div class="btn-group">
    <button type="button" class="btn btn-xs btn-@theme table-orderable" data-id="{{ $key }}" data-direction="1">
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

        $.post('{!! $resource !!}/' + key, {_method: 'PUT', _orderable: direction}, function (data) {
            if (data.status) {
                $.pjax.reload('#pjax-container');
                $.admin.toastr.success(data.message);
            }
        });
    });
</script>
