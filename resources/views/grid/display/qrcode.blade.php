<a
    href="javascript:void(0);"
    class="grid-column-qrcode text-muted"
    data-content="{!! $img !!}"
    tabindex='0'
>
    <i class="fa fa-qrcode"></i>
</a>&nbsp;{!! $value !!}

<script>
    $('.grid-column-qrcode').popover({
        html: true,
        container: 'body',
        trigger: 'focus'
    });
</script>
