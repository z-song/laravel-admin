<div class="box">
    <div class="box-header">

        @if( !$grid->getFilter()->usedModal() )
            {!! $grid->renderFilter() !!}
        @endif

        {!! $grid->renderHeaderTools() !!}
        {!! $grid->renderCreateButton() !!}
        {!! $grid->renderExportButton() !!}

        @if( $grid->getFilter()->usedModal() )
            {!! $grid->renderFilter() !!}
        @endif


    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tr>
                @foreach($grid->columns() as $column)
                <th>{{$column->getLabel()}}{!! $column->sorter() !!}</th>
                @endforeach
            </tr>

            @foreach($grid->rows() as $row)
            <tr {!! $row->getHtmlAttributes() !!}>
                @foreach($grid->columnNames as $name)
                <td>{!! $row->column($name) !!}</td>
                @endforeach
            </tr>
            @endforeach
        </table>
    </div>
    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>