<div class="box">
    @if(isset($title))
    <div class="box-header with-border">
        <h3 class="box-title"> {{ $title }}</h3>
    </div>
    @endif

    @if ( $grid->showTools() || $grid->showExportBtn() || $grid->showCreateBtn() )
    <div class="box-header with-border">
        <div class="pull-right">
            {!! $grid->renderColumnSelector() !!}
            {!! $grid->renderExportButton() !!}
            {!! $grid->renderCreateButton() !!}
        </div>
        @if ( $grid->showTools() )
        <div class="pull-left">
            {!! $grid->renderHeaderTools() !!}
        </div>
        @endif
    </div>
    @endif

    {!! $grid->renderFilter() !!}

    {!! $grid->renderHeader() !!}

    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <div class="tables-container">
            <div class="table-wrap table-main">
                <table class="table grid-table" id="{{ $grid->tableID }}">
                    <thead>
                        <tr>
                            @foreach($grid->visibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($grid->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($grid->visibleColumnNames() as $name)
                            <td {!! $row->getColumnAttributes($name) !!} class="column-{!! $name !!}">
                                {!! $row->column($name) !!}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>

                    {!! $grid->renderTotalRow() !!}

                </table>
            </div>

            @if($grid->leftVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-left">
                <table class="table grid-table">
                    <thead>
                    <tr>
                        @foreach($grid->leftVisibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($grid->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($grid->leftVisibleColumns() as $column)
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

                    {!! $grid->renderTotalRow($grid->leftVisibleColumns()) !!}

                </table>
            </div>
            @endif

            @if($grid->rightVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-right">
                <table class="table grid-table">
                    <thead>
                    <tr>
                        @foreach($grid->rightVisibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($grid->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($grid->rightVisibleColumns() as $column)
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

                    {!! $grid->renderTotalRow($grid->rightVisibleColumns()) !!}

                </table>
            </div>
            @endif
        </div>
    </div>

    {!! $grid->renderFooter() !!}

    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
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
    var theadHeight = $('.table-main thead tr')[0].getBoundingClientRect().height;
    $('.table-fixed thead tr').outerHeight(theadHeight);

    var tfootHeight = $('.table-main tfoot tr').outerHeight();
    $('.table-fixed tfoot tr').outerHeight(tfootHeight);

    $('.table-main tbody tr').each(function(i, obj) {
        var height = obj.getBoundingClientRect().height;

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

    $('.{{ $rowName }}-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {

        var id = $(this).data('id');
        var index = $(this).closest('tr').index();

        if (this.checked) {
        $.admin.grid.select(id);
            $('.table-main tbody tr').eq(index).css('background-color', '#ffffd5');
            $('.table-fixed-left tbody tr').eq(index).css('background-color', '#ffffd5');
            $('.table-fixed-right tbody tr').eq(index).css('background-color', '#ffffd5');
        } else {
        $.admin.grid.unselect(id);
            $('.table-main tbody tr').eq(index).css('background-color', '');
            $('.table-fixed-left tbody tr').eq(index).css('background-color', '');
            $('.table-fixed-right tbody tr').eq(index).css('background-color', '');
        }
    }).on('ifClicked', function () {

        var id = $(this).data('id');

        if (this.checked) {
            $.admin.grid.unselect(id);
        } else {
            $.admin.grid.select(id);
        }

        var selected = $.admin.grid.selected().length;

        if (selected > 0) {
            $('.{{ $allName }}-btn').show();
        } else {
            $('.{{ $allName }}-btn').hide();
        }

        $('.{{ $allName }}-btn .selected').html("{{ trans('admin.grid_items_selected') }}".replace('{n}', selected));
    });
</script>
