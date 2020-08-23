<div class="card card-@theme card-outline">
    <div class="card-header with-border">
        <h3 class="card-title">{{ $form->title() }}</h3>

        <div class="card-tools">
            {!! $form->renderTools() !!}
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    {!! $form->open() !!}

    <div class="card-body">

        @if(!$tabObj->isEmpty())
            @include('admin::form.tab', compact('tabObj'))
        @else
            <div class="fields-group">

                @if($form->hasRows())
                    @foreach($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    @foreach($layout->columns() as $column)
                        <div class="col-md-{{ $column->width() }}">
                            @foreach($column->fields() as $field)
                                {!! $field->render() !!}
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        @endif

    </div>
    <!-- /.card-body -->

    {!! $form->renderFooter() !!}

    @foreach($form->getHiddenFields() as $field)
        {!! $field->render() !!}
    @endforeach

<!-- /.card-footer -->
    {!! $form->close() !!}
</div>

<script>
    $('form.{{ $class }}').submit(function (e) {
        e.preventDefault();
        $(this).find('div.cascade-group.d-none :input').attr('disabled', true);
    });
</script>

@if(!$tabObj->isEmpty())
<script>
    var hash = document.location.hash;
    if (hash) {
        $('.nav-tabs a[href="' + hash + '"]').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        history.pushState(null,null, e.target.hash);
    });

    if ($('.has-error').length) {
        $('.has-error').each(function () {
            var tabId = '#'+$(this).closest('.tab-pane').attr('id');
            $('li a[href="'+tabId+'"] i').removeClass('d-none');
        });

        var first = $('.has-error:first').closest('.tab-pane').attr('id');
        $('li a[href="#'+first+'"]').tab('show');
    }
</script>
@endif

@if($confirm)
<script>
    $('form.{{ $class }} button[type=submit]').click(function (e) {
        e.preventDefault();
        var form = $(this).parents('form');
        $.admin.swal.fire({
            title: "{{ $confirm }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "{{ trans('admin.confirm') }}",
            cancelButtonText: "{{ trans('admin.cancel') }}",
        }).then(function (result) {
            if (result.value) {
                form.submit();
            }
        });
    });
</script>
@endif

<script>
    $('form.{{ $class }}').on('submit', function (e) {
        e.preventDefault();

        $form = $(this);

        var data = new FormData(this);
        data.append('_form_save', true);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            statusCode: {
                422: function(xhr) {
                    $form.find('.is-invalid').removeClass('is-invalid');
                    $form.find('.validation-error').remove();

                    for(var field in xhr.responseJSON.validation) {
                        var name = 'field-'+field.replace(/\./, '_');
                        var $el = $('.form-control.'+name);
                        $el.addClass('is-invalid');

                        xhr.responseJSON.validation[field].forEach(function (error, index) {
                            $el.closest('.col-sm-8').prepend('<label class="col-form-label validation-error text-danger">' +
                                '<i class="fas fa-bell"></i>' +
                                error+'</label>');
                        });
                    }
                }
            },
            success: function (data) {
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
    });
</script>

