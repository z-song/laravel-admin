$(document).on('click', '.ie-content-{{ $name }} .ie-submit', function () {
    var $popover = $(this).closest('.ie-content');

    @isset($val)
        {{ $val }}
    @else
        var val = $popover.find('.ie-input').val();
    @endisset

    var original = $popover.data('trigger').data('original');

    if (val == original) {
        $('[data-toggle="popover"]').popover('hide');
        return;
    }

    var data = {
        _token: LA.token,
        _method: 'PUT',
        _edit_inline: true,
    };
    data[$(this).parent().data('name')] = val;

    $.ajax({
        url: "{{ $resource }}/" + $(this).parent().data('key'),
        type: "POST",
        data: data,
        success: function (data) {
            toastr.success(data.message);

            {{ $slot }}

            $popover.data('trigger').data('value', val)
                .data('original', val);

            $('[data-toggle="popover"]').popover('hide');
        },
        statusCode: {
            422: function(xhr) {
                $popover.find('.error').empty();
                var errors = xhr.responseJSON.errors;
                for (var key in errors) {
                    $popover.find('.error').append('<div><i class="fa fa-times-circle-o"></i> '+errors[key]+'</div>')
                }
            }
        }
    });
});
