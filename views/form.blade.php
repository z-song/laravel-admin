<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>

        <div class="box-tools">
            {!! $form->renderHeaderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! $form->open(['class' => "form-horizontal"]) !!}
        <div class="box-body">

            @if(!$tabObj->isEmpty())
                @include('admin::form.tab', compact('tabObj'))
            @else
                <div class="fields-group">
                    @foreach($form->fields() as $field)
                        @if( ! $field instanceof \Encore\Admin\Form\Field\HasMany)
                            {!! $field->render() !!}
                        @endif
                    @endforeach
                </div>
                <div class="fields-group">
                    @foreach($form->fields() as $field)
                        @if( $field instanceof \Encore\Admin\Form\Field\HasMany)
                            {!! $field->render() !!}
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @if( ! $form->isMode(\Encore\Admin\Form\Builder::MODE_VIEW))
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @endif
            <div class="col-sm-{{$width['label']}}">

            </div>
            <div class="col-sm-{{$width['field']}}">

                {!! $form->submitButton() !!}

                {!! $form->resetButton() !!}

            </div>

        </div>

        @foreach($form->getHiddenFields() as $hiddenField)
            {!! $hiddenField->render() !!}
        @endforeach

        <!-- /.box-footer -->
    {!! $form->close() !!}
</div>

