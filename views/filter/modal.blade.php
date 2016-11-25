<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filter-modal"><i class="fa fa-filter"></i>&nbsp;&nbsp;{{ trans('admin::lang.filter') }}</a>
</div>

<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('admin::lang.filter') }}</h4>
            </div>
            <form action="/{!! $grid->resource() !!}" method="get" pjax-container>
                <div class="modal-body">
                    <div class="form">
                        @foreach($filters as $filter)
                            <div class="form-group">
                                {!! $filter->render() !!}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit">{{ trans('admin::lang.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>