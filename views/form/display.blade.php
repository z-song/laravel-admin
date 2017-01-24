<div class="form-group">
    <label class="col-sm-2 col-lg-2 control-label">{{$label}}</label>
    <div class="col-sm-8 col-lg-8">
        <div class="box box-solid box-default no-margin">
            <!-- /.box-header -->
            <div class="box-body">
                {!! $value !!}&nbsp;
            </div><!-- /.box-body -->
        </div>

        @include('admin::form.help-block')

    </div>
</div>