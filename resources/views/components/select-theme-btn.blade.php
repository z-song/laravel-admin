<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">قالب <b class="caret"></b></a>
    <ul class="dropdown-menu">
        @foreach (config('admin.site_themes') as $key => $name)
            @php($user_theme = Admin::user()->site_theme ?? config('admin.default_site_theme'))
            <li data-value="{{ $key }}" @class([
                'select-site-theme',
                'active' => $key === $user_theme,
            ])>
                <a href="#">
                    {{ $name }}
                </a>
            </li>
        @endforeach
    </ul>
</li>
<script>
    $(document).ready(function() {
        $('.select-site-theme').unbind('click').on('click', function() {
            let themeKey = $(this).data('value');
            $.ajax({
                url: "{{ route('admin.users.site-settings.change-theme') }}",
                data: {
                    'theme_key': themeKey
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                success: function() {
                    window.location.reload()
                }
            });
        });
    });
</script>
