<span class="ie-wrap">
    <a
        href="javascript:void(0);"
        class="{{ $trigger }}"
        data-editinline="popover"
        data-target="{{ $target }}"
        data-value="{{ $value }}"
        data-original="{{ $value }}"
        data-key="{{ $key }}"
        data-name="{{ $name }}"
    >
        <span class="ie-display">{{ $display }}</span>

        <i class="fa fa-edit" style="visibility: hidden;"></i>
    </a>
</span>

<template>
    <template id="{{ $target }}">
        <div class="ie-content ie-content-{{ $name }}" data-type="{{ $type ?? '' }}">
            <div class="ie-container">
                @yield('field')
                <div class="error"></div>
            </div>
            <div class="ie-action">
                <button class="btn btn-@color btn-sm ie-submit">{{ __('admin.submit') }}</button>
                <button class="btn btn-default btn-sm ie-cancel">{{ __('admin.cancel') }}</button>
            </div>
        </div>
    </template>
</template>

<style>
    .ie-wrap>a {
        padding: 3px;
        border-radius: 3px;
        color:#777;
    }

    .table-table tr:hover .ie-wrap>a>i {
        visibility: visible !important;
    }

    .ie-action button {
        margin: 10px 0 10px 10px;
        float: right;
    }

    .ie-container  {
        width: 250px;
        position: relative;
    }

    .ie-container .error {
        color: #dd4b39;
        font-weight: 700;
    }
</style>

@yield('assert')
