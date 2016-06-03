<div class="form-group" style="margin:10px 0px 30px 0px">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">
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
                        {!! $row->actions() !!}
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="btn-group pull-left" style="margin-right: 10px">
            <a href="/{{$grid->resource()}}" class="btn btn-sm btn-primary">{{ Lang::get('admin::lang.list') }}</a>
            <a href="/{{$grid->resource()}}/create" class="btn btn-sm btn-success">{{ Lang::get('admin::lang.new') }}</a>
        </div>
    </div>
</div>