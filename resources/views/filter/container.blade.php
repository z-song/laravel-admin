<div class="box-header with-border {{ $expand?'':'hide' }}" id="{{ $filterID }}">
    <form action="{!! $action !!}" class="form-horizontal" pjax-container method="get">
        <div class="box-body">
            <div class="fields-group">
                @foreach($filters as $filter)
                    {!! $filter->render() !!}
                @endforeach
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="btn-group pull-left">
                    <button class="btn btn-info submit"><i class="fa fa-search"></i>&nbsp;&nbsp;{{ trans('admin.search') }}</button>
                </div>
                <div class="btn-group pull-left " style="margin-left: 10px;">
                    <a href="{!! $action !!}" class="btn btn-default"><i class="fa fa-undo"></i>&nbsp;&nbsp;{{ trans('admin.reset') }}</a>
                </div>
            </div>
        </div>
    </form>
</div>