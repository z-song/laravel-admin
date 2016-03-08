@extends('admin::admin')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{$grid->title()}}</h3>

            <div class="box-tools">
                {!! $grid->renderFilter() !!}
                <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="/{{$grid->resource()}}/create" class="btn btn-sm btn-success">{{ Lang::get('admin::lang.new') }}</a>
                    {{--<a href="/{{$grid->resource()}}/export" class="btn btn-sm btn-primary">{{ Lang::get('admin::lang.export') }}</a>--}}
                </div>

            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    @foreach($grid->columns() as $column)
                    <th>{{$column->getLabel()}}{!!$column->sorter()!!}</th>
                    @endforeach
                    <th>{{ Lang::get('admin::lang.action') }}</th>
                </tr>

                @foreach($grid->rows() as $row)
                <tr {!! $row->attrs() !!}>
                    @foreach($grid->columnNames as $name)
                    <td>{!! $row->column($name) !!}</td>
                    @endforeach
                    <td>
                        {!! $grid->renderActions($row->id) !!}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer clearfix">
            {!! $grid->paginator() !!}
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection

