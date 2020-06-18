@if(!$holdAll)
<div class="btn-group {{ $all }}-btn" style="display:none;margin-right: 5px;">
    <a class="btn btn-sm btn-default hidden-xs"><span class="selected"></span></a>
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
$('.{{ $all }}').iCheck({checkboxClass:'icheckbox_minimal-blue'});

$('.{{ $all }}').on('ifChanged', function(event) {
    if (this.checked) {
        $('.{{ $row }}-checkbox').iCheck('check');
    } else {
        $('.{{ $row }}-checkbox').iCheck('uncheck');
    }
}).on('ifClicked', function () {
    if (this.checked) {
        $.admin.grid.selects = {};
    } else {
        $('.{{ $row }}-checkbox').each(function () {
            var id = $(this).data('id');
            $.admin.grid.select(id);
        });
    }

    var selected = $.admin.grid.selected().length;

    if (selected > 0) {
        $('.{{ $all }}-btn').show();
    } else {
        $('.{{ $all }}-btn').hide();
    }

    $('.{{ $all }}-btn .selected')
        .html("{{ trans('admin.grid_items_selected') }}".replace('{n}', selected));
});
</script>
