<div class="box">

    <div class="box-header">
        <div class="btn-group pull-left">
            <a class="btn btn-primary menu-tools" data-action="expand-all"><i class="fa fa-plus-square-o"></i>&nbsp;{{ trans('admin::lang.expand') }}</a>
            <a class="btn btn-primary menu-tools" data-action="collapse-all"><i class="fa fa-minus-square-o"></i>&nbsp;{{ trans('admin::lang.collapse') }}</a>
        </div>

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each('admin::menu.branch', $items, 'branch')
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