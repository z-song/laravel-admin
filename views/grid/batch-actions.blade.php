<input type="checkbox" class="grid-select-all" />&nbsp;

<div class="btn-group">
    <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        {{ trans('admin::lang.action') }} <span class="fa fa-caret-down"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            <li><a href="#" class="grid-batch-{{ $action['id'] }}">{{ $action['title'] }}</a></li>
        @endforeach
    </ul>
</div>