<thead>
<tr class="quick-create">
    <td colspan="{{ $columnCount }}"
        style="height: 47px;padding-left: 57px;background-color: #f9f9f9; vertical-align: middle;">

            <span class="create" style="color: #bdbdbd;cursor: pointer;display: block;">
                 <i class="fa fa-plus"></i>&nbsp;{{ __('admin.quick_create') }}
            </span>

        <form class="form-inline create-form" style="display: none;" method="post">
            @foreach($fields as $field)
                &nbsp;{!! $field->render() !!}
            @endforeach
            &nbsp;
            <button class="btn btn-primary btn-sm">{{ __('admin.submit') }}</button>&nbsp;
            <a href="javascript:void(0);" class="cancel">{{ __('admin.cancel') }}</a>
            {{ csrf_field() }}
        </form>
    </td>
</tr>
</thead>

<script>
    $('.quick-create .create').click(function () {
        $('.quick-create .create-form').show();
        $(this).hide();
    });

    $('.quick-create .cancel').click(function () {
        $('.quick-create .create-form').hide();
        $('.quick-create .create').show();
    });

    $('.quick-create .create-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ request()->url() }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function (data, textStatus, jqXHR) {
                console.info(data);

                if (data.status == true) {
                    $.admin.toastr.success(data.message, '', {positionClass: "toast-top-center"});
                    $.admin.reload();
                    return;
                }

                if (typeof data.validation !== 'undefined') {
                    $.admin.toastr.warning(data.message, '', {positionClass: "toast-top-center"})
                }
            },
            error: function (XMLHttpRequest, textStatus) {
                if (typeof XMLHttpRequest.responseJSON === 'object') {
                    $.admin.toastr.error(XMLHttpRequest.responseJSON.message, '', {
                        positionClass: "toast-top-center",
                        timeOut: 10000
                    });
                }
            }
        });
        return false;
    });
</script>
