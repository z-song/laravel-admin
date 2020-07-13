<a
    href="javascript:void(0);"
    class="{{ $class }}"
    data-type="{{ $type }}"
    data-pk="{{ $key }}"
    data-url="{{ $url }}"
    data-value="{{ $value }}"
    {!! $attributes !!}>
    {{ $type === 'select' ? '' : $value }}
</a>

<script require="editable">
    $.fn.editable.defaults.params = function (params) {
        params._editable = 1;
        params._method = 'PUT';
        return params;
    };

    $.fn.editable.defaults.error = function (data) {
        var msg = '';
        if (data.responseJSON.errors) {
            $.each(data.responseJSON.errors, function (k, v) {
                msg += v + "\n";
            });
        }
        return msg
    };

    $.fn.editable.defaults.success = function(response){
        if (response.status){
            $.admin.toastr.success(response.message, '', {positionClass:"toast-top-center"});
        } else {
            $.admin.toastr.error(response.message, '', {positionClass:"toast-top-center"});
        }
    };
</script>

<script>
    $('.{{ $class }}').editable(@json($options));
</script>
