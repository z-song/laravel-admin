<input type="checkbox" class="{{ $class }}" {{ $checked }} data-key="{{ $key }}" />

<script require="toggle">
    $('.{{ $class }}').bootstrapToggle({
        size:'xs',
        width: 60,
        on: '{{ $states['on']['text'] }}',
        off: '{{ $states['off']['text'] }}',
        onstyle: '{{ $states['on']['style'] }}',
        offstyle: '{{ $states['off']['style'] }}'
    }).change(function() {

        var key = $(this).data('key');
        var value = this.checked ? '{{ $states['on']['value'] }}' : '{{ $states['off']['value'] }}';

        $.ajax({
            url: "{{ $resource }}/" + key,
            type: "POST",
            data: {
                "{{ $name }}": value,
                _method: 'PUT'
            },
        }).done(function (data) {
            if (data.status) {
                $.admin.toastr.success(data.message);
            } else {
                $.admin.toastr.warning(data.message);
            }
        });
    });
</script>
