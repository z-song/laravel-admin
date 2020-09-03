<div class="card-header {{ $expand?'':'d-none' }} filter-box" id="{{ $filterID }}">
    <form action="{!! $action !!}" class="form-horizontal" pjax-container method="get">

        <div class="row">
            @foreach($layout->columns() as $column)
            <div class="col-md-{{ $column->width() }}">
                <div class="card-body">
                    <div class="fields-group">
                        @foreach($column->filters() as $filter)
                            {!! $filter->render() !!}
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <div class="row">
                <div class="col-md-{{ $layout->columns()->first()->width() }} row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="btn-group float-left">
                            <button class="btn btn-@color submit btn-sm"><i
                                        class="fa fa-search"></i>&nbsp;&nbsp;{{ admin_trans('admin.search') }}</button>
                        </div>
                        <div class="btn-group float-left " style="margin-left: 10px;">
                            <a href="{!! $action !!}" class="btn btn-default btn-sm"><i
                                        class="fa fa-undo"></i>&nbsp;&nbsp;{{ admin_trans('admin.reset') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
