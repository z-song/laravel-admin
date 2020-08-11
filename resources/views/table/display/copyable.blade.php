<a href="javascript:void(0);"
   class="table-column-copyable text-muted"
   data-content="{{ $original }}"
   title="Copied!"
   data-placement="bottom"
>
    <i class="fa fa-copy"></i>
</a>&nbsp;{!! $value !!}


<script>
    $('.table-column-copyable').click(function (e) {
        var content = $(this).data('content');

        var temp = $('<input>');

        $("body").append(temp);
        temp.val(content).select();
        document.execCommand("copy");
        temp.remove();

        $(this).tooltip('show');
    });
</script>
