<span class="dropdown">
<form action="{{ $action }}" pjax-container style="display: inline-block;">
    <a href="javascript:void(0);" class="dropdown-toggle {{ empty($value) ? '' : 'text-yellow' }}" data-toggle="dropdown">
        <i class="fa fa-filter"></i>
    </a>
    <ul class="dropdown-menu" role="menu" style="padding: 10px;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);left: -70px;border-radius: 0;">

        <li>
            <ul style='padding: 0;'>
            <li class="checkbox icheck-@theme" style="margin: 0;">

                <input id="@id" type="checkbox" class="{{ $class['all'] }}" {{ $allCheck }}/>
                <label style="width: 100%;padding: 3px;" for="@id">
                    &nbsp;&nbsp;&nbsp;{{ __('admin.all') }}
                </label>
            </li>
                <li class="divider"></li>
                @foreach($options as $key => $label)
                <li class="checkbox icheck-@theme" style="margin: 0;">
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
            </ul>
        </li>
        <li class="divider"></li>
        <li class="text-right">
            <button class="btn btn-sm btn-flat btn-@theme float-left" data-loading-text="{{ __('admin.search') }}..."><i class="fa fa-search"></i>&nbsp;&nbsp;{{ __('admin.search') }}</button>
            <button class="btn btn-sm btn-flat btn-default" type="reset" data-loading-text="..."><i class="fa fa-undo"></i></button>
        </li>
    </ul>
</form>
</span>

<script>
    $('.{{ $class['all'] }}').change(function () {
        $('.{{ $class['item'] }}').prop('checked', this.checked);
        return false;
    });
</script>
