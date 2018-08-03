<div class="btn-group" style="margin-right: 10px">
    <a class="btn btn-sm btn-twitter {{ $btn_class }}">
        <i class="fa fa-filter"></i>&nbsp;&nbsp;&nbsp;{{ trans('admin.filter') }}
    </a>

    @if($scopes->isNotEmpty())
    <button type="button" class="btn btn-sm btn-twitter dropdown-toggle" data-toggle="dropdown">

        <span>{{ $current_label }}</span>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        @foreach($scopes as $scope)
            {!! $scope->render() !!}
        @endforeach
        <li role="separator" class="divider"></li>
        <li><a href="{{ $url_no_scopes }}">{{ trans('admin.none') }}</a></li>
    </ul>
    @endif
</div>