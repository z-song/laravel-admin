<div class="card card-@color card-outline">
    <div class="card-header">
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
{{--                        <div class="col-md-{{ $column->width() }}">--}}
                            @foreach($column->fields() as $field)
                                {!! $field->render() !!}
                            @endforeach
{{--                        </div>--}}
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
<script selector="form.{{ $class }}">
    var $form = $(this);
    $form.find('button[type=submit]').click(function (e) {
        e.preventDefault();
        $.admin.confirm({title: "{{ $confirm }}",}).then(function (result) {
            if (result.value) {
                $form.submit();
            }
        });
    });
</script>
@endif

<script selector="form.{{ $class }}">
    $.admin.initForm($(this));
</script>

