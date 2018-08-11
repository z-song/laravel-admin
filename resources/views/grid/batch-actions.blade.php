<input type="checkbox" class="{{ $selectAllName }}" />&nbsp;

<div class="btn-group">
    <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown">
        {{ trans('admin.action') }}
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            <li><a href="#" class="{{ $action->getElementClass(false) }}">{{ $action->getTitle() }}</a></li>
        @endforeach
    </ul>
</div>