{{--inline edit popover--}}

<style>
    .ie-action button {
        margin: 10px 0 10px 10px;
        float: right;
    }

    .ie-container  {
        width: 250px;
        position: relative;
    }
</style>

<script>
    $(document).on('click', '.ie-action .ie-cancel', function () {
        $('[data-toggle="popover"]').popover('hide');
    });

    $('body').on('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('[data-toggle="popover"]').length === 0
            && $(e.target).parents('.popover.in').length === 0) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
</script>
