<!-- Main Footer -->
<footer class="main-footer">
    @if(empty(config('admin.footer_view')))
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            @if(config('admin.show_environment'))
                <strong>Env</strong>&nbsp;&nbsp; {!! config('app.env') !!}
            @endif

            &nbsp;&nbsp;&nbsp;&nbsp;

            @if(config('admin.show_version'))
                <strong>Version</strong>&nbsp;&nbsp; {!! \Encore\Admin\Admin::VERSION !!}
            @endif

        </div>
        <!-- Default to the left -->
        <strong>Powered by <a href="https://github.com/z-song/laravel-admin" target="_blank">laravel-admin</a></strong>
    @else
        @include(config('admin.footer_view'))
    @endif
</footer>
