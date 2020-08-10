{{--<div class="btn-group" style="margin-right: 5px" data-toggle="buttons">--}}
{{--    <label class="btn btn-sm btn-dropbox {{ $btn_class }} {{ $expand ? 'active' : '' }}" title="{{ trans('admin.filter') }}">--}}
{{--        <input type="checkbox" class="d-none"><i class="fa fa-filter"></i><span class="hidden-xs">&nbsp;&nbsp;{{ trans('admin.filter') }}</span>--}}
{{--    </label>--}}

{{--    @if($scopes->isNotEmpty())--}}
{{--    <div class="btn-group">--}}
{{--        <button type="button" class="btn btn-sm btn-dropbox dropdown-toggle dropdown-icon" data-toggle="dropdown">--}}
{{--            <span>{{ $label }}</span>--}}
{{--        </button>--}}
{{--        <div class="dropdown-menu" role="menu">--}}
{{--            @foreach($scopes as $scope)--}}
{{--                {!! $scope->render() !!}--}}
{{--            @endforeach--}}
{{--            <div class="divider"></div>--}}
{{--            <a href="{{ $cancel }}" class="dropdown-item">{{ trans('admin.cancel') }}</a>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    @endif--}}
{{--</div>--}}

<div class="btn-group">
    <button type="button" class="btn btn-default">
        <input type="checkbox" class="d-none"><i class="fa fa-filter"></i><span class="hidden-xs">&nbsp;&nbsp;{{ trans('admin.filter') }}</span>
    </button>

    @if($scopes->isNotEmpty())
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
            <span>{{ $label }}</span>
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Dropdown link</a>
            <a class="dropdown-item" href="#">Dropdown link</a>
            <div class="divider"></div>
            <a href="{{ $cancel }}" class="dropdown-item">{{ trans('admin.cancel') }}</a>
        </div>
    </div>
    @endif
</div>

<script>
var $btn = $('.{{ $btn_class }}');
var $filter = $('#{{ $filter_id }}');

$btn.unbind('click').click(function (e) {
    if ($filter.is(':visible')) {
        $filter.addClass('d-none');
    } else {
        $filter.removeClass('d-none');
    }
});
</script>
