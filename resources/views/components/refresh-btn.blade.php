<li>
    <a href="javascript:void(0);" class="{{ $__id }}">
        <i class="fa fa-refresh"></i>
    </a>
</li>
<script>
    $('.{{ $__id }}').off('click').on('click', function() {
        $.admin.reload();
        $.admin.toastr.success('{{ admin_trans('admin.refresh_succeeded') }}', '', {positionClass:"toast-top-center"});
    });
</script>
