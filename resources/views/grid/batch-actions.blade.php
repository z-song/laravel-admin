<input type="checkbox" class="grid-select-all" />&nbsp;

<div class="btn-group">
    <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown">
        {{ trans('admin.action') }}
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            <li><a href="#" class="grid-batch-{{ $action['id'] }}">{{ $action['title'] }}</a></li>
        @endforeach
    </ul>
</div>