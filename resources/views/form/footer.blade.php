<div class="box-footer">

    {{ csrf_field() }}

    <div class="col-md-{{ $width['label'] }}">
    </div>

    <div class="col-md-{{ $width['field'] }}">

        @if (in_array('submit', $buttons))
            <div class="btn-group pull-left">
                <button type="submit" class="btn btn-primary" style="margin-right: 8px; padding: 6px 30px">
                    {{ trans('admin.submit') }}
                </button>
            </div>

            @foreach ($submit_redirects as $value => $redirect)
                @if (in_array($redirect, $checkboxes))
                    <label class="pull-left submit-redirect-btn btn btn-primary btn-outline" style="margin: 0 10px 0 0;">
                        <input type="checkbox" class="after-submit" name="after-save" value="{{ $value }}"
                            {{ $default_check == $redirect ? 'checked' : '' }}>
                        <span>{{ trans("admin.{$redirect}") }}</span>
                    </label>
                @endif
            @endforeach

        @endif

        @if (in_array('reset', $buttons))
            <div class="btn-group pull-right">
                <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
            </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.after-submit').on('ifToggled', function(elm) {
            $(elm.target).parents('.box-footer').find('button[type=submit]').click();
        });

        $(document).on('pjax:start', function(e) {
            $('.after-submit').unbind('ifToggled');
        });
    })
</script>
