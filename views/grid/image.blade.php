<div class="box">
    <div class="box-header">

        <h3 class="box-title"></h3>

        <span style="position: absolute;left: 10px;top: 5px;">
            {!! $grid->renderHeaderTools() !!}
        </span>

        <div class="box-tools">
            {!! $grid->renderFilter() !!}
            {!! $grid->renderExportButton() !!}
            {!! $grid->renderCreateButton() !!}
        </div>

    </div>
    <!-- /.box-header -->

    <div class="box-footer">
        <ul class="mailbox-attachments clearfix">
            @foreach($grid->rows() as $row)
                <li>
                    <span class="mailbox-attachment-icon has-img">
                        <img src="{!! isset($server) ? $server : config('admin.upload.host') !!}/{!! $row->column($image_column) !!}" alt="Attachment">
                    </span>
                    <div class="mailbox-attachment-info">
                        <a href="#" class="mailbox-attachment-name" style="word-break:break-all;">
                            <i class="fa fa-camera"></i>&nbsp;&nbsp;
                            {!! isset($text_column) ? $row->column($text_column) : '' !!}
                        </a>
                        <span class="mailbox-attachment-size">
                          <input type="checkbox" class="grid-item" data-id="{{ $row->id() }}" />
                            <span class="pull-right">
                                {!! $row->column('__actions__') !!}
                                <a href="{!! isset($server) ? $server : config('admin.upload.host') !!}/{!! $row->column($image_column) !!}" target="_blank" download="custom-filename.jpg">
                                    <i class="fa fa-cloud-download"></i>
                                </a>
                            </span>
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>