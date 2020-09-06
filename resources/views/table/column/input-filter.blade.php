<span class="dropdown column-filter">
    <form action="{!! $action !!}" pjax-container style="display: inline-block;">
    <a href="javascript:void(0);" class="dropdown-toggle {{ empty($value) ? '' : 'text-yellow' }}" data-toggle="dropdown">
        <i class="fa fa-filter"></i>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li class="dropdown-item">
            <input type="text" name="{{ $name }}" value="{{ $value }}" class="form-control input-sm {{ $class }}" autocomplete="off"/>
        </li>
        <li class="dropdown-divider"></li>
        <li class="dropdown-item text-right">
            <button class="btn btn-sm btn-@color column-filter-submit float-left" data-loading-text="{{ __("admin.search") }}..."><i class="fa fa-search"></i>&nbsp;&nbsp;{{ __("admin.search") }}</button>
            <button class="btn btn-sm btn-default column-filter-all" data-loading-text="..."><i class="fa fa-undo"></i></button>
        </li>
    </ul>
    </form>
</span>

<style>
    .column-filter .dropdown-menu {
        padding: 10px;
        top: 12px !important;
    }

    .column-filter .dropdown-item {
        padding: 0.25rem 0rem;
    }
</style>

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
