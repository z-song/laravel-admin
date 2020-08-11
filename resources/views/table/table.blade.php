<script>
    $.admin.initTable({!!  $__table  !!});
</script>

<div class="card table-card">
    @if(isset($title))
    <div class="card-header">
        <h3 class="card-title"> {{ $title }}</h3>
    </div>
    @endif

    @if ( $table->showTools() || $table->showExportBtn() || $table->showCreateBtn() )
    <div class="card-header">
        <div class="card-tools">
            {!! $table->renderColumnSelector() !!}
            {!! $table->renderExportButton() !!}
            {!! $table->renderCreateButton() !!}
        </div>
        @if ( $table->showTools() )
        <div class="float-left">
            {!! $table->renderHeaderTools() !!}
        </div>
        @endif
    </div>
    @endif

    {!! $table->renderFilter() !!}

    {!! $table->renderHeader() !!}

    <!-- /.box-header -->
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-table table-head-fixed" id="{{ $table->tableID }}">
            <thead>
                <tr>
                    @foreach($table->visibleColumns() as $column)
                    <th {!! $column->formatHtmlAttributes() !!}>{!! $column->getLabel() !!}{!! $column->renderHeader() !!}</th>
                    @endforeach
                </tr>
            </thead>

            @if ($table->hasQuickCreate())
                {!! $table->renderQuickCreate() !!}
            @endif

            <tbody>

                @if($table->rows()->isEmpty() && $table->showDefineEmptyPage())
                    @include('admin::table.empty-table')
                @endif

                @foreach($table->rows() as $row)
                <tr {!! $row->getRowAttributes() !!}>
                    @foreach($table->visibleColumnNames() as $name)
                    <td {!! $row->getColumnAttributes($name) !!}>
                        {!! $row->column($name) !!}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>

            {!! $table->renderTotalRow() !!}

        </table>

    </div>

    {!! $table->renderFooter() !!}

    <div class="card-footer clearfix">
        {!! $table->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>
