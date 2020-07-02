<span class="dropdown column-filter">
    <form action="{!! $action !!}" pjax-container style="display: inline-block;">
    <a href="javascript:void(0);" class="dropdown-toggle {{ empty($value) ? '' : 'text-yellow' }}" data-toggle="dropdown">
        <i class="fa fa-filter"></i>
    </a>
    <ul class="dropdown-menu" role="menu" style="padding: 10px;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);left: -70px;border-radius: 0;">
        <li>
            <input type="text" name="{{ $name }}" value="{{ $value }}" class="form-control input-sm {{ $class }}" autocomplete="off"/>
        </li>
        <li class="divider"></li>
        <li class="text-right">
            <button class="btn btn-sm btn-flat btn-primary column-filter-submit pull-left" data-loading-text="{{ __("admin.search") }}..."><i class="fa fa-search"></i>&nbsp;&nbsp;{{ __("admin.search") }}</button>
            <button class="btn btn-sm btn-flat btn-default column-filter-all" data-loading-text="..."><i class="fa fa-undo"></i></button>
        </li>
    </ul>
    </form>
</span>

<script>
    $('.column-filter .dropdown-menu input').click(function(e) {
        e.stopPropagation();
    });
</script>

@if($dp)
<script require="datetimepicker">
    $('.{{ $class }}').datetimepicker(@json($dp));
</script>
@endif
