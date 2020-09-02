<div class="card">
    @if(isset($title))
    <div class="card-header">
        <h3 class="card-title"> {{ $title }}</h3>
    </div>
    @endif

    @if ( $table->showTools() || $table->showExportBtn() || $table->showCreateBtn() )
    <div class="card-header">
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
    <div class="card-body table-responsive p-0">
        <div class="tables-container">
            @if($table->leftVisibleColumns()->isNotEmpty())
                <div class="table-wrap table-fixed table-fixed-left">
                    <table class="table table-table table-hover" id="{{ $table->tableID }}">
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

            <div class="table-wrap table-main">
                <table class="table table-table table-hover">
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

            @if($table->rightVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-right">
                <table class="table table-table table-hover">
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

    .table-wrap tbody>tr.active {
        color: #212529;
        background-color: rgba(0,0,0,.075);
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
    var $mtr = $('.table-main tbody>tr');
    var $ltr = $('.table-fixed-left tbody>tr');
    var $rtr = $('.table-fixed-right tbody>tr');

    $('.table-fixed thead tr').outerHeight($('.table-main thead tr').outerHeight());
    $('.table-fixed tfoot tr').outerHeight($('.table-main tfoot tr').outerHeight());

    $mtr.each(function(i, obj) {
        var height = $(obj).outerHeight();

        $ltr.eq(i).outerHeight(height);
        $rtr.eq(i).outerHeight(height);
    });

    if ($('.table-main').width() >= $('.table-main').prop('scrollWidth')) {
        $('.table-fixed').hide();
    }

    $('.table-wrap tbody tr').mouseover(function () {
        var index = $(this).index();
        $mtr.eq(index).addClass('active');
        $ltr.eq(index).addClass('active');
        $rtr.eq(index).addClass('active');
    }).mouseout(function () {
        var index = $(this).index();
        $mtr.eq(index).removeClass('active');
        $ltr.eq(index).removeClass('active');
        $rtr.eq(index).removeClass('active');
    });

    $('.table-row-checkbox').change(function () {

        var index = $(this).closest('tr').index();

        if (this.checked) {
            $mtr.eq(index).addClass('selected');
            $ltr.eq(index).addClass('selected');
            $rtr.eq(index).addClass('selected');
            $.admin.table.select($(this).data('id'));
        } else {
            $mtr.eq(index).removeClass('selected');
            $ltr.eq(index).removeClass('selected');
            $rtr.eq(index).removeClass('selected');
            $.admin.table.unselect($(this).data('id'));
        }
    });
</script>
