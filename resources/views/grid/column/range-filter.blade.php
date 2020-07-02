<span class="dropdown">
<form action="{{ $action }}" pjax-container style="display: inline-block;">
    <a href="javascript:void(0);" class="dropdown-toggle {{ empty(array_filter($value)) ? '' : 'text-yellow' }}" data-toggle="dropdown">
        <i class="fa fa-filter"></i>
    </a>
    <ul class="dropdown-menu" role="menu" style="padding: 10px;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);left: -70px;border-radius: 0;">
        <li>
            <input type="text" class="form-control input-sm {{ $class['start'] }}" name="{{ $name }}[start]" value="{{ $value['start'] }}" autocomplete="off"/>
        </li>
        <li style="margin: 5px;"></li>
        <li>
            <input type="text" class="form-control input-sm {{ $class['start'] }}" name="{{ $name }}[end]"  value="{{ $value['end'] }}" autocomplete="off"/>
        </li>
        <li class="divider"></li>
        <li class="text-right">
            <button class="btn btn-sm btn-primary btn-flat column-filter-submit pull-left" data-loading-text="{{ __('admin.search') }}..."><i class="fa fa-search"></i>&nbsp;&nbsp;{{ __('admin.search') }}</button>
            <button class="btn btn-sm btn-default btn-flat column-filter-all" data-loading-text="..."><i class="fa fa-undo"></i></button>
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
    $('.{{ $class['start'] }},.{{ $class['end'] }}').datetimepicker(@json($dp));
</script>
@endif
