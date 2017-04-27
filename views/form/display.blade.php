<div class="form-group">
    <label class="col-sm-{{$width['label']}} control-label">{{$label}}</label>
    <div class="col-sm-{{$width['field']}}">
        <div class="box box-solid box-default no-margin">
            <!-- /.box-header -->
            <div class="box-body">
                {!! $value !!}&nbsp;
            </div><!-- /.box-body -->
        </div>

        @include('admin::form.help-block')

    </div>
</div>