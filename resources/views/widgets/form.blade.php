<form {!! $attributes !!}>
    <div class="box-body fields-group">

        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach

    </div>

    <!-- /.box-body -->
    <div class="box-footer">
    @if ($method != 'GET')
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @endif
        <div class="col-md-2"></div>

        <div class="col-md-8">
            <div class="btn-group pull-left">
                <button type="reset" class="btn btn-warning pull-right">{{ trans('admin.reset') }}</button>
            </div>
            <div class="btn-group pull-right">
                <button type="submit" class="btn btn-info pull-right">{{ trans('admin.submit') }}</button>
            </div>

        </div>

    </div>
</form>
