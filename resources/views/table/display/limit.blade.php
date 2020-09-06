
<div class="limit-text">
    <span class="text">{{ $value }}</span>
    &nbsp;<a href="javascript:void(0);" class="table-limit-more">&nbsp;<i class="fa fa-angle-double-down"></i></a>
</div>
<div class="limit-text d-none">
    <span class="text">{{ $original }}</span>
    &nbsp;<a href="javascript:void(0);" class="table-limit-more">&nbsp;<i class="fa fa-angle-double-up"></i></a>
</div>

<script>
    $('.table-limit-more').click(function () {
        $(this).parent('.limit-text').toggleClass('d-none').siblings().toggleClass('d-none');
    });
</script>
