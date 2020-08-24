@if(!$holdAll)
<div class="btn-group table-select-all-btn mr-2" style="display:none;">
    <a class="btn btn-sm btn-default hidden-xs"><span class="selected" data-tpl="{{ trans('admin.table_items_selected') }}"></span></a>
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    @if(!$actions->isEmpty())
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            @if($action instanceof \Encore\Admin\Actions\BatchAction)
                <li>{!! $action->render() !!}</li>
            @else
                <li><a href="#" class="{{ $action->getElementClass(false) }}">{!! $action->render() !!} </a></li>
            @endif
        @endforeach
    </ul>
    @endif
</div>
@endif

<script>
    $('.table-select-all').on('change', function(event) {
        $.admin.table.toggleAll(this.checked);
    });
</script>
