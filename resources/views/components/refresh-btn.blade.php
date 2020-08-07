<li>
    <a href="javascript:void(0);" @el>
        <i class="fa fa-refresh"></i>
    </a>
</li>
<script>
    @el.off('click').on('click', function() {
        $.admin.reload();
        $.admin.toastr.success('{{ admin_trans('admin.refresh_succeeded') }}', '', {positionClass:"toast-top-center"});
    });
</script>
