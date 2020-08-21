<a href="javascript:void(0);" class="table-column-qrcode text-muted">
    <i class="fa fa-qrcode"></i>
</a>&nbsp;{!! $value !!}

<script>
    $('.table-column-qrcode').popover({
        html: true,
        content: "<img src='https://api.qrserver.com/v1/create-qr-code/?size={{ $width }}x{{ $height }}&data={{ $content }}' style='height:{{ $height }}px;width:{{ $width }}px;'/>",
        container: 'body',
        trigger: 'focus',
        sanitize: false,
    });
</script>
