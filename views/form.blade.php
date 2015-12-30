@extends('admin::admin')

@section('content')
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{$form->options()['title']}}</h3>
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
                <a href="/users" type="submit" class="btn btn-default">返回列表</a>
                {!! $form->submit() !!}
            </div>
            <!-- /.box-footer -->
        {!! $form->close() !!}
    </div>
    <!-- /.box -->
@endsection

