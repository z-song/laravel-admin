<template>
    <div class="ie-content ie-content-{{ $name }}" id="{{ $target }}">
        <div class="ie-container">
            {{ $slot }}
        </div>
        <div data-key="{{ $key }}" data-name="{{ $name }}" class="ie-action">
            <button class="btn btn-primary btn-sm ie-submit">{{ __('admin.submit') }}</button>
            <button class="btn btn-default btn-sm ie-cancel">{{ __('admin.cancel') }}</button>
        </div>
    </div>
</template>
