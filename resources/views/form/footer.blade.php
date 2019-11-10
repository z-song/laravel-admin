@if(!empty($checkboxes))
<div class="box-footer">
    <div class="col-sm-{{$width['label']}}"><label class="control-label">Option</label>
    </div>
    <div class="col-sm-{{$width['field']}}">
        @foreach($submit_redirects as $value => $redirect)
            @if(in_array($redirect, $checkboxes))
            <label class="pull-left" style="margin: 5px 10px 0 0;">
                <input type="checkbox" class="after-submit" name="after-save" value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}> {{ trans("admin.{$redirect}") }}
            </label>
            @endif
        @endforeach
    </div>
</div>
@endif

<div class="box-footer">

    {{ csrf_field() }}

    <div class="col-md-{{$width['label']}}">
    </div>
    <div class="col-md-{{$width['field']}}">
        <div class="btn-group pull-right">
        @if(in_array('submit', $buttons))
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
        @else
            <button type="submit" class="btn btn-primary" disabled>{{ trans('admin.submit') }}</button>
        @endif
        </div>
        <div class="btn-group pull-left">
        @if(in_array('reset', $buttons))
            <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
        @else
            <button type="reset" class="btn btn-warning" disabled>{{ trans('admin.reset') }}</button>
        @endif
        </div>
    </div>
</div>
