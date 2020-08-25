<span class="dropdown column-filter">
<form action="{{ $action }}" pjax-container style="display: inline-block;">
    <a href="javascript:void(0);" class="dropdown-toggle {{ empty($value) ? '' : 'text-yellow' }}" data-toggle="dropdown">
        <i class="fa fa-filter"></i>
    </a>
    <ul class="dropdown-menu" role="menu" style="padding: 10px;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);left: -70px;border-radius: 0;">

        <li class="dropdown-item checkbox icheck-@color" style="margin: 0;">
            <input id="@id" type="checkbox" class="{{ $class['all'] }}" {{ $allCheck }}/>
            <label style="width: 100%;padding: 3px;" for="@id">
                &nbsp;&nbsp;&nbsp;{{ __('admin.all') }}
            </label>
        </li>
        <li class="dropdown-divider"></li>
        @foreach($options as $key => $label)
        <li class="dropdown-item checkbox icheck-@color" style="margin: 0;">
            <input
                id="@id"
                type="checkbox"
                class="{{ $class['item'] }}"
                name="{{ $name }}[]"
                value="{{ $key }}"
                {{ in_array($key, $value) ? 'checked' : '' }}/>
            <label style="width: 100%;padding: 3px;" for="@id">
                &nbsp;&nbsp;&nbsp;{{ $label }}
            </label>
        </li>
        @endforeach

        <li class="dropdown-divider"></li>
        <li class="dropdown-item text-right">
            <button class="btn btn-sm btn-@color float-left" data-loading-text="{{ __('admin.search') }}..."><i class="fa fa-search"></i>&nbsp;&nbsp;{{ __('admin.search') }}</button>
            <button class="btn btn-sm btn-default" type="reset" data-loading-text="..."><i class="fa fa-undo"></i></button>
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

    .column-filter [class*=icheck-] {
         margin-top: 0px!important;
         margin-bottom: 0px!important;
    }
</style>

<script>
    $('.{{ $class['all'] }}').change(function () {
        $('.{{ $class['item'] }}').prop('checked', this.checked);
        return false;
    });
</script>
