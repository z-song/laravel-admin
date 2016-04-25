<form action="" method="get" accept-charset="UTF-8" class="form-horizontal">
    <div class="box-body">

        <div class="form-group">
            <label for="caption" class="col-sm-2 control-label">id</label>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                    <input type="text" id="caption" name="id" class="form-control" placeholder="输入 id">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="caption" class="col-sm-2 control-label">电话</label>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                    <input type="text" id="caption" name="mobile" class="form-control" placeholder="输入 mobile">
                </div>
            </div>
        </div>
    </div>

    <!-- /.box-body -->
    <div class="box-footer">
        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
        <div class="col-sm-2">

        </div>
        <div class="col-sm-6">

            <div class="btn-group pull-left">
                <button type="submit" class="btn btn-info pull-right">提交</button>
            </div>

        </div>
    </div>
    <!-- /.box-footer -->
</form>