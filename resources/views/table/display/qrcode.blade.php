<a href="javascript:void(0);" class="table-column-qrcode text-muted" data-value="{{ $content }}">
    <i class="fa fa-qrcode"></i>
</a>&nbsp;{!! $value !!}

<script>
    $('.table-column-qrcode').popover({
        html: true,
        content: function () {
            var content = $(this).data('value');
            return "<img src='https://api.qrserver.com/v1/create-qr-code/?size={{ $width }}x{{ $height }}&data="+content+"' style='height:{{ $height }}px;width:{{ $width }}px;'/>";
        },
        container: 'body',
        trigger: 'focus',
        sanitize: false,
    });
</script>
