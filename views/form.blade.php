@extends('admin::admin')

@section('content')
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $form->title() }}</h3>

            <div class="box-tools">
                <div class="btn-group pull-right">
                    <a href="/" class="btn btn-sm btn-warning"><i class="fa fa-trash"></i>&nbsp;{{ Lang::get('admin::lang.delete') }}</a>
                </div>
                <div class="btn-group pull-right" style="margin-right: 10px">
                    {!! $form->back() !!}
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
            {!! $form->open(['class' => "form-horizontal"]) !!}
            <div class="box-body">

                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-sm-2">

                </div>
                <div class="col-sm-6">

                    <div class="btn-group pull-right">
                    {!! $form->submit() !!}
                    </div>

                    <div class="btn-group pull-left">
                        <input type="reset" class="btn btn-warning" value="撤销"/>
                    </div>

                </div>

            </div>
            <!-- /.box-footer -->
        {!! $form->close() !!}
    </div>
    <!-- /.box -->
@endsection

