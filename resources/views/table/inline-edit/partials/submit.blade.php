<script>
$(document).off('click', '.ie-content .ie-submit').on('click', '.ie-content .ie-submit', function () {

    var $popover = $(this).closest('.ie-content');
    var $trigger = $popover.data('trigger');

    switch($popover.data('type')) {
        case 'input':
        case 'textarea':
        case 'datetime':
            var val = $popover.find('.ie-input').val();
            var label = val;
            break;
        case 'select':
            var val = $popover.find('.ie-input').val();
            var label = $popover.find('.ie-input>option:selected').data('label');
            break;
        case 'multiple-select':
            var val = [];
            var label = [];
            $popover.find('.ie-input>option:selected').each(function(){
                val.push($(this).val());
                label.push($(this).data('label'));
            });
            label = label.join(';');
            break;
        case 'checkbox':
            var val = [];
            var label = [];
            $popover.find('.ie-input:checked').each(function(){
                val.push($(this).val());
                label.push($(this).data('label'));
            });
            label = label.join(';');
            break;
        case 'radio':
            var val = $popover.find('.ie-input:checked').val();
            var label = $popover.find('.ie-input:checked').data('label');
            break;
    }

    var original = $trigger.data('original');

    if (val == original) {
        $('[data-editinline="popover"]').popover('hide');
        return;
    }

    var data = {};
    data[$trigger.data('name')] = val;
    data['__inline_edit'] = 1;

    $.put({
        url: "{{ $resource }}/" + $trigger.data('key'),
        data: data,
        statusCode: {
            422: function(xhr) {
                $popover.find('.error').empty();
                $popover.find('.error').append('<div class=mt-2><i class="fa fa-times-circle-o"></i> '+xhr.responseJSON.message+'</div>')
            }
        }
    }).done(function (data) {
        if (data.status === true) {
            $.admin.toastr.success(data.message);
            $popover.data('display').html(label);
            $trigger.data('value', val).data('original', val);
            $('[data-editinline="popover"]').popover('hide');
        } else {
            $.admin.toastr.error(data.message);
        }
    });
});
</script>
