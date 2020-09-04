<input type="checkbox" class="{{ $class }}" {{ $checked }} data-key="{{ $key }}" />

<script>
    $('.{{ $class }}').bootstrapSwitch({
        size:'mini',
        onText: '{{ $states['on']['text'] }}',
        offText: '{{ $states['off']['text'] }}',
        onColor: '{{ $states['on']['color'] }}',
        offColor: '{{ $states['off']['color'] }}',
        onSwitchChange: function(event, state){

            $(this).val(state ? 'on' : 'off');

            var key = $(this).data('key');
            var value = $(this).val();
            var _status = true;

            $.ajax({
                url: "{{ $resource }}/" + key,
                type: "POST",
                async:false,
                data: {
                    "{{ $name }}": value,
                    _token: LA.token,
                    _method: 'PUT'
                },
                success: function (data) {
                    if (data.status)
                        toastr.success(data.message);
                    else
                        toastr.warning(data.message);
                },
                complete:function(xhr,status) {
                    if (status == 'success')
                        _status = xhr.responseJSON.status;
                }
            });

            return _status;
        }
    });
</script>
