 <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        @foreach($tabObj->getTabs() as $tab)
            <li class="nav-item">
                <a href="#{{ $tab->id }}" data-toggle="tab" class="nav-link{{ $tab->active ? ' active' : '' }}">
                    {{ $tab->title }} <i class="fa fa-exclamation-circle text-red d-none"></i>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content fields-group mt-3">
        @foreach($tabObj->getTabs() as $tab)
            <div class="tab-pane {{ $tab->active ? 'active' : '' }}" id="{{ $tab->id }}">
                @foreach($tab->getRows() as $row)
                    <div class="row">
                        @foreach($row->getColumns() as $column)
                            <div class="{{ $column->width() }}">
                                @foreach($column->getFields() as $field)
                                    {!! $field->render() !!}
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach

    </div>
</div>
