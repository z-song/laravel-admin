$('.{{ $trigger }}').popover({
    html: true,
    container: 'body',
    trigger: 'manual',
    placement: function (context, source) {
        var position = $(source).position();

        if (position.left < 100) {
            return "right";
        }

        if (position.top < 110) {
            return "bottom";
        }

        return "bottom";
    },
    content: function () {
        @isset($content)
            return {{ $content }}
        @else
            return $(this)
                .parents('.ie-wrap')
                .find('template')
                .html();
        @endisset
    }
}).on('shown.bs.popover', function (e) {

    var $popover = $('#'+$(this).data('target'));
    var $display = $(this).parents('.ie-wrap').find('.ie-display');
    var $trigger = $(this);

    $popover.data('display', $display);
    $popover.data('trigger', $trigger);

    {{ $popover }}

}).click(function () {
    $('[data-toggle="popover"]').popover('hide');
    $(this).popover('toggle');
});
