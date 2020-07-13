<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{{ $all }}" target="_blank" class="btn btn-sm btn-twitter" title="{{ trans('admin.export') }}">
        <i class="fa fa-download"></i>
        <span class="hidden-xs"> {{ trans('admin.export') }}</span>
    </a>
    <button type="button" class="btn btn-sm btn-twitter dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href="{{ $all }}" target="_blank">{{ trans('admin.all') }}</a></li>
        <li><a href="{{ $page }}" target="_blank">{{ trans('admin.current_page') }}</a></li>
        <li style="display: none;"><a href="{{ $selected }}" target="_blank" class='{{ $name }}'>{{ trans('admin.selected_rows') }}</a></li>
    </ul>
</div>

<script>
    $('.{{ $name }}').click(function (e) {
        e.preventDefault();
        var rows = $.admin.table.selected().join();
        if (!rows) {
            return false;
        }
        var href = $(this).attr('href').replace('__rows__', rows);
        location.href = href;
    });
</script>
