{{--inline edit popover--}}

<span class="ie-wrap">
    <a
        href="javascript:void(0);"
        class="{{ $trigger }}"
        data-toggle="popover"
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
        <div class="ie-content ie-content-{{ $name }}">
            <div class="ie-container">
                @yield('field')
                <div class="error"></div>
            </div>
            <div class="ie-action">
                <button class="btn btn-primary btn-sm ie-submit">{{ __('admin.submit') }}</button>
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

    .ie-wrap>a:hover {
        text-decoration: none;
        background-color: #ddd;
        color:#777;
    }

    .ie-wrap>a:hover i {
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

<script>
    $(document).on('click', '.ie-action .ie-cancel', function () {
        $('[data-toggle="popover"]').popover('hide');
    });

    $('body').on('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('[data-toggle="popover"]').length === 0
            && $(e.target).parents('.popover.in').length === 0) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
</script>

@yield('assert')
