<form {!! $attributes !!}>
    <div class="card card-outline card-@color">

        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
        </div>


        <div class="card-body">
            @foreach($fields as $field)
                {!! $field->render() !!}
            @endforeach
        </div>

        <!-- /.card-body -->
        @if(count($buttons) > 0)
        <div class="card-footer row">
            <div class="col-{{$width['label']}}"></div>

            <div class="col-{{$width['field']}}">
                @if(in_array('reset', $buttons))
                <div class="btn-group float-left">
                    <button type="reset" class="btn btn-warning float-right">{{ admin_trans('admin.reset') }}</button>
                </div>
                @endif

                @if(in_array('submit', $buttons))
                <div class="btn-group float-right">
                    <button type="submit" class="btn btn-@color float-right">{{ admin_trans('admin.submit') }}</button>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</form>

<div class="card card-outline card-@color d-none form-result">
    <div class="card-header">
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <div class="card-body"></div>
</div>

@if($confirm)
    <script selector="form#{{ $id }}">
        var $form = $(this);
        $form.find('button[type=submit]').click(function (e) {
            e.preventDefault();
            $.admin.confirm({
                title: "{{ $confirm }}",
            }).then(function (result) {
                if (result.value) {
                    $form.submit();
                }
            });
        });
    </script>
@endif

<script selector="form#{{ $id }}">
    $.admin.initForm($(this));
</script>
