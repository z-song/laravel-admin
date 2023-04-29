@if (isset($title))
    <div class="box-header with-border text-center no-padding grid-header">
        <h3 class="box-title"> {{ $title }}</h3>
    </div>
@endif
<div class="box grid-box">

    @if ($grid->showTools() || $grid->showExportBtn() || $grid->showCreateBtn())
        <div class="box-header with-border">
            <div class="pull-right">
                {!! $grid->renderColumnSelector() !!}
                {!! $grid->renderExportButton() !!}
                {!! $grid->renderCreateButton() !!}
            </div>
            @if ($grid->showTools())
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
        <table class="table table-hover grid-table" id="{{ $grid->tableID }}">
            <thead>
                <tr>
                    @foreach ($grid->visibleColumns() as $column)
                        <th {!! $column->formatHtmlAttributes() !!}>{!! $column->getLabel() !!}{!! $column->renderHeader() !!}</th>
                    @endforeach
                </tr>
            </thead>

            @if ($grid->hasQuickCreate())
                {!! $grid->renderQuickCreate() !!}
            @endif

            <tbody>

                @if ($grid->rows()->isEmpty() && $grid->showDefineEmptyPage())
                    @include('admin::grid.empty-grid')
                @endif

                @foreach ($grid->rows() as $row)
                    <tr {!! $row->getRowAttributes() !!}>
                        @foreach ($grid->visibleColumnNames() as $name)
                            <td {!! $row->getColumnAttributes($name) !!}>
                                {!! $row->column($name) !!}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>

            {!! $grid->renderTotalRow() !!}

        </table>

    </div>

    {!! $grid->renderFooter() !!}

    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>

<script>
    $(document).ready(function() {
        $(".grid-box table > tbody > tr").on('click', function(e) {
            let tagName = e.target.tagName.toUpperCase();
            if (tagName == 'A') return;
            if ($(e.target).parentsUntil('tr').length > 0 &&
                $(e.target).parentsUntil('tr').last()[0].tagName != 'HTML' &&
                $(e.target).parentsUntil('tr').has('a').length > 0) {
                return;
            }

            let dest = $(e.target).parents('tr').find('.row-action-show');
            if (dest.length > 0) {
                dest.click();
            }
        });

        $(".grid-box table > tbody > tr .row-action-show").each(function(index, elm) {
            $(elm).parents('tr').css('cursor', 'pointer');
        });

        $(document).on('pjax:start', function(e) {
            $(".grid-box table > tbody > tr").unbind('click');
        });
    });
</script>
