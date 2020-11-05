@foreach($rows as $row)
    @if($row->html)
        {!! $row->html !!}
    @else
    <div class="row">
        @foreach($row->getColumns() as $column)
            <div class="{{ $column->width() }}">
                @foreach($column->getFields() as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>
        @endforeach
    </div>
    @endif
@endforeach
