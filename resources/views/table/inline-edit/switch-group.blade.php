<tr style="height: 28px;">
    <td class="p-1 border-0"><strong><small>{{ $label }}:</small></strong>&nbsp;&nbsp;&nbsp;</td>
    <td class="p-1 border-0"><input type="checkbox" class="{{ $class }}" {{ $checked }} data-key="{{ $key }}" /></td>
</tr>

<script require="toggle" selector=".{{ $class }}" all="1">
    $(this).bootstrapToggle(@json($options)).change(function() {
        var key = $(this).data('key');
        var value = this.checked ? '{{ $states['on']['value'] }}' : '{{ $states['off']['value'] }}';

        $.put({
            url: "{{ $resource }}/" + key,
            data: {"{{ $name }}": value, __inline_edit: 1},
        }).done(function (data) {
            $.admin.toastr.show(data);
        });
    });
</script>
