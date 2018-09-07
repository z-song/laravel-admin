<div class="form-group ">
    <label class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-8">
        @if($wrapped)
        <div class="box box-solid box-default no-margin box-show">
            <!-- /.box-header -->
            <div class="box-body">
                {!! $content !!}&nbsp;
            </div><!-- /.box-body -->
        </div>
        @else
            {!! $content !!}
        @endif
    </div>
</div>