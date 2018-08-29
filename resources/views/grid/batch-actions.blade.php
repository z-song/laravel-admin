<input type="checkbox" class="{{ $selectAllName }}" />&nbsp;

<div class="btn-group">
    <a class="btn btn-sm btn-default">&nbsp;<span class="hidden-xs">{{ trans('admin.action') }}</span></a>
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            <li><a href="#" class="{{ $action->getElementClass(false) }}">{{ $action->getTitle() }}</a></li>
        @endforeach
    </ul>
</div>