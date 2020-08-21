<div class="card">
    @if(isset($title))
    <div class="card-header with-border">
        <h3 class="card-title"> {{ $title }}</h3>
    </div>
    @endif

    @if ( $table->showTools() || $table->showExportBtn() || $table->showCreateBtn() )
    <div class="card-header with-border">
        <div class="float-right">
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

    <!-- /.card-header -->
    <div class="card-body table-responsive no-padding">
        <div class="tables-container">
            <div class="table-wrap table-main">
                <table class="table table-table" id="{{ $table->tableID }}">
                    <thead>
                        <tr>
                            @foreach($table->visibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($table->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($table->visibleColumnNames() as $name)
                            <td {!! $row->getColumnAttributes($name) !!} class="column-{!! $name !!}">
                                {!! $row->column($name) !!}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>

                    {!! $table->renderTotalRow() !!}

                </table>
            </div>

            @if($table->leftVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-left">
                <table class="table table-table">
                    <thead>
                    <tr>
                        @foreach($table->leftVisibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($table->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($table->leftVisibleColumns() as $column)
                                @php
                                    $name = $column->getName()
                                @endphp
                                <td {!! $row->getColumnAttributes($name) !!} class="column-{!! $name !!}">
                                    {!! $row->column($name) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>

                    {!! $table->renderTotalRow($table->leftVisibleColumns()) !!}

                </table>
            </div>
            @endif

            @if($table->rightVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-right">
                <table class="table table-table">
                    <thead>
                    <tr>
                        @foreach($table->rightVisibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($table->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($table->rightVisibleColumns() as $column)
                                @php
                                $name = $column->getName()
                                @endphp
                                <td {!! $row->getColumnAttributes($name) !!} class="column-{!! $name !!}">
                                    {!! $row->column($name) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>

                    {!! $table->renderTotalRow($table->rightVisibleColumns()) !!}

                </table>
            </div>
            @endif
        </div>
    </div>

    {!! $table->renderFooter() !!}

    <div class="card-footer clearfix">
        {!! $table->paginator() !!}
    </div>
    <!-- /.card-body -->
</div>


<style>
    .tables-container {
        position:relative;
    }

    .tables-container table {
        margin-bottom: 0px !important;
    }

    .tables-container table th, .tables-container table td {
        white-space:nowrap;
    }

    .table-wrap table tr .active {
        background: #f5f5f5;
    }

    .table-main {
        overflow-x: auto;
        width: 100%;
    }

    .table-fixed {
        position:absolute;
        top: 0px;
        background:#ffffff;
        z-index:10;
    }

    .table-fixed-left {
        left:0;
        box-shadow: 7px 0 5px -5px rgba(0,0,0,.12);
    }

    .table-fixed-right {
        right:0;
        box-shadow: -5px 0 5px -5px rgba(0,0,0,.12);
    }
</style>

<script>
    $.admin.initTable({!!  $__table  !!});
</script>

<script>
    var theadHeight = $('.table-main thead tr').outerHeight();
    $('.table-fixed thead tr').outerHeight(theadHeight);

    var tfootHeight = $('.table-main tfoot tr').outerHeight();
    $('.table-fixed tfoot tr').outerHeight(tfootHeight);

    $('.table-main tbody tr').each(function(i, obj) {
        var height = $(obj).outerHeight();

        $('.table-fixed-left tbody tr').eq(i).outerHeight(height);
        $('.table-fixed-right tbody tr').eq(i).outerHeight(height);
    });

    if ($('.table-main').width() >= $('.table-main').prop('scrollWidth')) {
        $('.table-fixed').hide();
    }

    $('.table-wrap tbody tr').on('mouseover', function () {
        var index = $(this).index();

        $('.table-main tbody tr').eq(index).addClass('active');
        $('.table-fixed-left tbody tr').eq(index).addClass('active');
        $('.table-fixed-right tbody tr').eq(index).addClass('active');
    });

    $('.table-wrap tbody tr').on('mouseout', function () {
        var index = $(this).index();

        $('.table-main tbody tr').eq(index).removeClass('active');
        $('.table-fixed-left tbody tr').eq(index).removeClass('active');
        $('.table-fixed-right tbody tr').eq(index).removeClass('active');
    });

    $('.{{ $rowName }}-checkbox').change(function () {

        var id = $(this).data('id');
        var index = $(this).closest('tr').index();

        if (this.checked) {
            $('.table-main tbody tr').eq(index).css('background-color', '#ffffd5');
            $('.table-fixed-left tbody tr').eq(index).css('background-color', '#ffffd5');
            $('.table-fixed-right tbody tr').eq(index).css('background-color', '#ffffd5');
        } else {
            $('.table-main tbody tr').eq(index).css('background-color', '');
            $('.table-fixed-left tbody tr').eq(index).css('background-color', '');
            $('.table-fixed-right tbody tr').eq(index).css('background-color', '');
        }

        $.admin.table.toggle($(this).data('id'));
    });
</script>
