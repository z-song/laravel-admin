<div class="form-group">
    <label class="col-sm-2 control-label">{{$label}}</label>
    <div class="col-sm-6">
        <div class="box box-solid box-default no-margin">
            <!-- /.box-header -->
            <div class="box-body">
                {!! $value !!}&nbsp;
            </div><!-- /.box-body -->
        </div>

        @include('admin::field.help-block')

    </div>
</div>