<div class="box box-widget {{ $expand?'':'hide' }}" id="{{ $filterID }}">
    <form action="{!! $action !!}" class="form-horizontal" pjax-container method="get">
        <div class="box-body">
            <div class="row">
                @foreach($layout->columns() as $column)
                <div class="col-md-{{ $column->width() }}">
                    <div class="fields-group">
                        @foreach($column->filters() as $filter)
                        {!! $filter->render() !!}
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer" with-border>
            <div class="btn-toolbar pull-right">
                <a href="{!! $action !!}" class="btn btn-default btn-sm"><i
                        class="fa fa-undo"></i>&nbsp;&nbsp;{{ trans('admin.reset') }}</a>
                <button class="btn btn-info submit btn-sm"><i
                        class="fa fa-search"></i>&nbsp;&nbsp;{{ trans('admin.search') }}</button>
            </div>
        </div>
        <!-- /.box-footer -->

    </form>
</div>