<div class="box">

    <div class="box-header">

        <div class="btn-group">
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="expand" title="{{ trans('admin.expand') }}">
                <i class="fa-regular fa-square-plus"></i> {{ trans('admin.expand') }}
            </a>
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="collapse" title="{{ trans('admin.collapse') }}">
                <i class="fa-regular fa-square-minus"></i> {{ trans('admin.collapse') }}
            </a>
        </div>

        @if($useSave)
        <div class="btn-group">
            <a class="btn btn-info btn-sm {{ $id }}-save" title="{{ trans('admin.save') }}">
                <i class="fa-regular fa-save"></i> <span class="hidden-xs"> {{ trans('admin.save') }}</span>
            </a>
        </div>
        @endif

        @if($useRefresh)
        <div class="btn-group">
            <a class="btn btn-warning btn-sm {{ $id }}-refresh" title="{{ trans('admin.refresh') }}">
                <i class="fa-regular fa-refresh"></i> <span class="hidden-xs"> {{ trans('admin.refresh') }}</span>
            </a>
        </div>
        @endif

        <div class="btn-group">
            {!! $tools !!}
        </div>

        @if($useCreate)
        <div class="btn-group pull-right">
            <a class="btn btn-success btn-sm" href="{{ url($path) }}/create"><i class="fa-regular fa-add"></i><span class="hidden-xs"> {{ trans('admin.new') }}</span></a>
        </div>
        @endif

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <div class="dd dd-tree" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
    <!-- /.box-body -->
</div>
