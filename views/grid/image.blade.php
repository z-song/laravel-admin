<div class="box">
    <div class="box-header">
        <h3 class="box-title"></h3>

        <div class="box-tools">

            {!! $grid->renderFilter() !!}

            @if($grid->allowExport())
                <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="/{{ $grid->exportUrl() }}" target="_blank" class="btn btn-sm btn-warning"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</a>
                </div>
            @endif

            @if($grid->allowCreation())
                <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="/{{$grid->resource()}}/create" class="btn btn-sm btn-success"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('admin::lang.new') }}</a>
                </div>
            @endif

        </div>
    </div>
    <!-- /.box-header -->

    <div class="box-footer">
        <ul class="mailbox-attachments clearfix">
            @foreach($grid->rows() as $row)
                <li>
                    <span class="mailbox-attachment-icon has-img"><img src="{!! isset($server) ? $server : config('admin.upload.host') !!}/{!! $row->column($image_column) !!}" alt="Attachment"></span>
                    <div class="mailbox-attachment-info">
                        <a href="#" class="mailbox-attachment-name" style="word-break:break-all;"><i class="fa fa-camera"></i>&nbsp;&nbsp;{!! isset($text_column) ? $row->column($text_column) : '' !!}</a>
                        <span class="mailbox-attachment-size">
                          <input type="checkbox" class="grid-item" data-id="{{ $row->id() }}">
                            <span class="pull-right">
                            @if($grid->allowActions())
                                {!! $row->actions() !!}
                                <a href="{!! isset($server) ? $server : config('admin.upload.host') !!}/{!! $row->column($image_column) !!}" target="_blank" download="custom-filename.jpg"><i class="fa fa-cloud-download"></i></a>
                            @endif
                            </span>
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="box-footer clearfix">
        <input type="checkbox" class="grid-select-all">&nbsp;&nbsp;&nbsp;
        @if($grid->allowBatchDeletion())
            <a class="btn btn-sm btn-danger batch-delete">{{ trans('admin::lang.batch_delete') }}</a>
        @endif

        <a class="btn btn-sm btn-primary grid-refresh"><i class="fa fa-refresh"></i></a>

        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>