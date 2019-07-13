{{--<input type="checkbox" class="{{ $selectAllName }}" />&nbsp;--}}

@if(!$isHoldSelectAllCheckbox)
<div class="btn-group {{$selectAllName}}-btn" style="display:none;margin-right: 5px;">
    <a class="btn btn-sm btn-default"><span class="hidden-xs selected"></span></a>
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    @if(!$actions->isEmpty())
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            <li><a href="#" class="{{ $action->getElementClass(false) }}">{!! $action->render() !!} </a></li>
        @endforeach
    </ul>
    @endif
</div>
@endif