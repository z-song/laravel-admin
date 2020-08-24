<form {!! $attributes !!}>
    <div class="card-body fields-group">
        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach

    </div>

    @if ($method != 'GET' && !$ajax)
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @endif

    <!-- /.card-body -->
    @if(count($buttons) > 0)
    <div class="card-footer row">
        <div class="col-md-{{$width['label']}}"></div>

        <div class="col-md-{{$width['field']}}">
            @if(in_array('reset', $buttons))
            <div class="btn-group float-left">
                <button type="reset" class="btn btn-warning float-right">{{ trans('admin.reset') }}</button>
            </div>
            @endif

            @if(in_array('submit', $buttons))
            <div class="btn-group float-right">
                <button type="submit" class="btn btn-@theme float-right">{{ trans('admin.submit') }}</button>
            </div>
            @endif
        </div>
    </div>
    @endif
</form>

<script>
    var $form = $('form#{{ $id }}');
    $form.submit(function (e) {
        e.preventDefault();
        var form = $(this);
        $(this).find('div.cascade-group.d-none :input').attr('disabled', true);

        @if($confirm)
        $.admin.swal({
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ trans('admin.submit') }}',
            cancelButtonText: '{{ trans('admin.cancel') }}',
            title: '{{ $confirm }}',
            text: ''
        }).then(function (result) {
            if (result.value == true) {
                form.submit();
            }
        });
        @endif

        @if($ajax)
        var data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (typeof data != 'object') {
                    $.admin.toastr.error('Oops something went wrong!');
                }

                if (data.status === true) {
                    if (data.message) {
                        $.admin.toastr.success(data.message);
                    }

                    if (data.refresh === true) {
                        $.admin.reload();
                    }

                    if (data.redirect) {
                        $.admin.redirect(data.redirect);
                    }
                } else {
                    $.admin.toastr.error(data.message);
                }
            }
        });
        return false;
        @endif
    });
</script>
