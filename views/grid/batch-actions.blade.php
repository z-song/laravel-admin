<input type="checkbox" class="grid-select-all" />&nbsp;

<div class="btn-group">
    <a class="btn btn-sm btn-default">  {{ trans('admin::lang.action') }}</a>
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            <li><a href="#" class="grid-batch-{{ $action['id'] }}">{{ $action['title'] }}</a></li>
        @endforeach
    </ul>
</div>