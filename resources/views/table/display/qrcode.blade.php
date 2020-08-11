<a
    href="javascript:void(0);"
    class="table-column-qrcode text-muted"
    data-content="{!! $img !!}"
    tabindex='0'
>
    <i class="fa fa-qrcode"></i>
</a>&nbsp;{!! $value !!}

<script>
    $('.table-column-qrcode').popover({
        html: true,
        container: 'body',
        trigger: 'focus'
    });
</script>
