@extends('admin::admin')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{$grid->option('title')}}</h3>

            <div class="box-tools">

                <div class="btn-group pull-left" style="margin-right: 20px">
                    <a href="/{{$grid->resource()}}/create" class="btn btn-sm btn-success">新增</a>
                    <a href="/{{$grid->resource()}}/export" class="btn btn-sm btn-primary">导出</a>
                </div>

                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    @foreach($grid->columns() as $column)
                    <th>{{$column->getLabel()}}</th>
                    @endforeach
                    <th>Actions</th>
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
            {!! $grid->pageRender() !!}
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection

