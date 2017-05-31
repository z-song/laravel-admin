<div class="box">
    <div class="box-header">
        <h3 class="box-title"></h3>

        @if($grid->usePagination() && $grid->usePerPageSelector())
        <div class="input-group pull-left" style="width: 170px;">
            <span class="input-group-addon"><small>{{ trans('admin::lang.show') }}</small></span>
            <select class="form-control input-xs per-page" name="per-page">
                {!! $grid->perPageOptions() !!}
            </select>
            <span class="input-group-addon"><small>{{ trans('admin::lang.items') }}</small></span>
        </div>
        @endif

        {!! $grid->renderFilter() !!}

        @if($grid->allowExport())
        <div class="btn-group pull-right" style="margin-right: 10px">
            <a href="/{{ $grid->exportUrl() }}" target="_blank" class="btn btn-sm btn-warning"><i class="fa fa-download"></i>&nbsp;&nbsp;{{ trans('admin::lang.export') }}</a>
        </div>
        @endif

        @if($grid->allowCreation())
        <div class="btn-group pull-right" style="margin-right: 10px">
            <a href="/{{$grid->resource()}}/create" class="btn btn-sm btn-success"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('admin::lang.new') }}</a>
        </div>
        @endif

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tr>
                <th><input type="checkbox" class="grid-select-all" /></th>
                @foreach($grid->columns() as $column)
                <th>{{$column->getLabel()}}{!! $column->sorter() !!}</th>
                @endforeach

                @if($grid->isOrderable())
                    <th>{{ trans('admin::lang.order') }}</th>
                @endif

                @if($grid->allowActions())
                    <th>{{ trans('admin::lang.action') }}</th>
                @endif
            </tr>

            @foreach($grid->rows() as $row)
            <tr {!! $row->getRowAttributes() !!}>
                <td><input type="checkbox" class="grid-item" data-id="{{ $row->id() }}" /></td>
                @foreach($grid->columnNames as $name)
                <td>{!! $row->column($name) !!}</td>
                @endforeach

                @if($grid->isOrderable())
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-info grid-order-up" data-id="{{ $row->id() }}"><i class="fa fa-caret-up fa-fw"></i></button>
                            <button type="button" class="btn btn-xs btn-default grid-order-down" data-id="{{ $row->id() }}"><i class="fa fa-caret-down fa-fw"></i></button>
                        </div>
                    </td>
                @endif

                @if($grid->allowActions())
                    <td>
                        {!! $row->actions() !!}
                    </td>
                @endif
            </tr>
            @endforeach
        </table>
    </div>
    <div class="box-footer clearfix">
        <input type="checkbox" class="grid-select-all" />&nbsp;&nbsp;&nbsp;
        @if($grid->allowBatchDeletion())
            <a class="btn btn-sm btn-danger batch-delete">{{ trans('admin::lang.batch_delete') }}</a>
        @endif

            <a class="btn btn-sm btn-primary grid-refresh"><i class="fa fa-refresh"></i></a>

        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>