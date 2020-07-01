<label class="pull-right perpage-selector">
    {{ trans('admin.show') }}
    <span class="dropup">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            {{ $perpage }}
            <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu">
        @foreach($options as $option)
        <li><a href="{!! request()->fullUrlWithQuery([$name => $option]) !!}" class="{{ $perpage == $option ? 'active' : '' }}">{{ $option }}</a></li>
        @endforeach
        </ul>
    </span>
    &nbsp;{{ trans('admin.entries') }}
</label>

<style>
    .perpage-selector {
        margin: 5px 10px 0 0;
        color: #777;
        font-weight: 400;
    }
    .perpage-selector a {
        color: #777;
    }
    .perpage-selector .dropdown-menu {
        min-width: 70px;
        left: -20px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }
    .perpage-selector .dropdown-menu a.active {
        background-color: #d2d6de;
    }
</style>
