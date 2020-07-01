
<div class="limit-text">
    <span class="text">{{ $value }}</span>
    &nbsp;<a href="javascript:void(0);" class="grid-limit-more">&nbsp;<i class="fa fa-angle-double-down"></i></a>
</div>
<div class="limit-text hide">
    <span class="text">{{ $original }}</span>
    &nbsp;<a href="javascript:void(0);" class="grid-limit-more">&nbsp;<i class="fa fa-angle-double-up"></i></a>
</div>

<script>
    $('.grid-limit-more').click(function () {
        $(this).parent('.limit-text').toggleClass('hide').siblings().toggleClass('hide');
    });
</script>
