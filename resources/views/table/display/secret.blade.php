
<span class="table-column-secret">
    <i class="fa fa-eye" style="cursor: pointer;"></i>
    &nbsp;
    <span class="secret-placeholder" style="vertical-align: middle;">{{ $dots }}</span>
    <span class="secret-content" style="display: none;">{!! $value !!}</span>
</span>

<script>
    $('.table-column-secret i').click(function () {
        $(this).toggleClass('fa-eye fa-eye-slash').parent().find('.secret-placeholder,.secret-content').toggle();
    });
</script>
