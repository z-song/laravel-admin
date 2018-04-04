<form {!! $attributes !!}>
    <div class="box-body fields-group">

        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach

    </div>

    <!-- /.box-body -->
    <div class="box-footer">
        @if( ! $method == 'GET')
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @endif
        <div class="form-group">
            <div class="col-md-{{ $labelWidth }}"></div>
            <div class="col-md-{{ $fieldWidth }}">
                <div class="btn-group pull-left">
                    <button type="reset" class="btn btn-warning pull-left">{{ trans('admin.reset') }}</button>
                </div>
                <div class="btn-group pull-right">
                    <button type="submit" class="btn btn-info pull-left">{{ trans('admin.submit') }}</button>
                </div>

            </div>
        </div>

    </div>
</form>