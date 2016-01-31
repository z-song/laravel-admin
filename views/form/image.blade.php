<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-image"></i></span>
            <a class='btn btn-default btn-flat' href='javascript:;'>
                选择图片...
                <input type="file" id="{{$id}}" name="{{$name}}" value="{{ old($column, $value) }}" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info-{{$id}}").html($(this).val());'>
            </a>
            &nbsp;
            <span class='label label-info' id="upload-file-info-{{$id}}">{{ old($column, $value) }}</span>
        </div>
        {!! $preview !!}
    </div>
</div>