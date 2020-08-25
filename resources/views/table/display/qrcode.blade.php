<a href="javascript:void(0);" class="table-column-qrcode text-muted" data-content="{{ $content }}">
    <i class="fa fa-qrcode"></i>
</a>&nbsp;{!! $value !!}

<script>
    $('.table-column-qrcode').popover({
        html: true,
        content: function () {
            console.log($(this));
            var content = $(this).data('content');
            return "<img src='https://api.qrserver.com/v1/create-qr-code/?size={{ $width }}x{{ $height }}&data="+content+"' style='height:{{ $height }}px;width:{{ $width }}px;'/>";
        },
        container: 'body',
        trigger: 'focus',
        sanitize: false,
    });
</script>
