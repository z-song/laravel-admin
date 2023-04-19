<div class="box-footer">

    {{ csrf_field() }}

    <div class="col-md-{{$width['label']}}">
    </div>

    <div class="col-md-{{$width['field']}}">

        @if(in_array('submit', $buttons))
        <div class="btn-group pull-left">
            <button type="submit" class="btn btn-info" style="margin-right: 8px; padding: 6px 30px">
                <i class="fa-regular fa-save"></i>
                {{ trans('admin.submit') }}
            </button>
        </div>

        @foreach($submit_redirects as $value => $redirect)
            @if(in_array($redirect, $checkboxes))
            <label class="pull-left" style="margin: 5px 10px 0 0;">
                <input type="checkbox" class="after-submit" name="after-save" value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}> {{ trans("admin.{$redirect}") }}
            </label>
            @endif
        @endforeach

        @endif

        @if(in_array('reset', $buttons))
        <div class="btn-group pull-right">
            <button type="reset" class="btn btn-warning">
                <i class="fa-regular fa-eraser"></i>
                {{ trans('admin.reset') }}
            </button>
        </div>
        @endif
    </div>
</div>