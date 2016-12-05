<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>

        <div class="box-tools">
            @if($form->allowDeletion())
            <div class="btn-group pull-right">
                <a href="javascript:void(0);" class="btn btn-sm btn-warning item_delete" data-id="{{ $id }}"><i class="fa fa-trash"></i>&nbsp;{{ trans('admin::lang.delete') }}</a>
            </div>
            @endif

            <div class="btn-group pull-right" style="margin-right: 10px">
                <a href="{{ $resource }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;{{ trans('admin::lang.list') }}</a>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! $form->open(['class' => "form-horizontal"]) !!}
        <div class="box-body">

            @foreach($form->fields() as $field)
                {!! $field->render() !!}
            @endforeach

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-sm-2">

            </div>
            <div class="col-sm-6">

                <div class="btn-group pull-right">
                {!! $form->submit() !!}
                </div>

                <div class="btn-group pull-left">
                    <input type="reset" class="btn btn-warning" value="{{ trans('admin::lang.reset') }}"/>
                </div>

            </div>

        </div>
        <!-- /.box-footer -->
    {!! $form->close() !!}
</div>

