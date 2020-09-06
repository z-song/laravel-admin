<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        @if(config('admin.show_environment'))
            <strong>Env</strong>&nbsp;&nbsp; {!! config('app.env') !!}
        @endif
        @if(config('admin.show_version'))
            <b>Version</b> {!! \Encore\Admin\Admin::VERSION !!}
        @endif
    </div>
    <strong>Powered by <a href="https://github.com/z-song/laravel-admin" target="_blank">laravel-admin</a></strong>
</footer>
