<div class="box">
    <div class="box-header">
        <h3 class="box-title"></h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a href="/{{ $path }}/create" class="btn btn-sm btn-success">{{ trans('admin::lang.new') }}</a>
            </div>

        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each('admin::tree.branch', $items, 'branch')
            </ol>
        </div>
    </div>
    <div class="box-footer clearfix">
        <div class="col-sm-2">

        </div>
        <div class="col-sm-6">

            <div class="btn-group pull-right">
                <button class="btn btn-info {{ $id }}-save">{{ trans('admin::lang.save') }}</button>
            </div>

            <div class="btn-group pull-left">
                <button class="btn btn-warning {{ $id }}-refresh">{{ trans('admin::lang.refresh') }}</button>
            </div>

        </div>
    </div>
    <!-- /.box-body -->
</div>