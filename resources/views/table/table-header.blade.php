<thead>
{{--组合表头--}}
@if($table->hasColumnGroup() && $columns->isNotEmpty())
{{--    @php($columns)--}}
    {{--                    第一行--}}
    <tr>
        @for($i = 0; $i < count($columns); $i++)
            @if($group = $columns[$i]->getGroup())
                <th colspan="{{ $group->count() }}">
                    {{ $group->title() }} {{ $group->help() }}
                </th>
                @php($i = $i + $group->count() - 1)
            @else
                <th rowspan="2" {!! $columns[$i]->formatHtmlAttributes() !!}>
                    {!! $columns[$i]->getLabel() !!}{!! $columns[$i]->renderHeader() !!}
                </th>
            @endif
        @endfor
    </tr>

    {{--                    第二行--}}
    <tr>
        @foreach($table->getGroupColumns() as $column)
            <th {!! $column->formatHtmlAttributes() !!}>
                {!! $column->getLabel() !!}{!! $column->renderHeader() !!}
            </th>
        @endforeach
    </tr>
@else
    {{--普通表头--}}
    <tr>
        @foreach($table->visibleColumns() as $column)
            <th {!! $column->formatHtmlAttributes() !!}>
                {!! $column->getLabel() !!}{!! $column->renderHeader() !!}
            </th>
        @endforeach
    </tr>
@endif
</thead>
