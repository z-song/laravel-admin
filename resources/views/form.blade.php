<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>
        <div class="box-tools">
            {!! $form->renderHeaderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    @php
        /** @var \Encore\Admin\Form\Builder $form */
           $_class = "";
           $rand_id = rand(1000, 5000);
       //    if(config('javascript.js_window.ajax_form')){
       //        \Admin::js('/js/jquery.form.min.js');
       //        \Admin::js('/js/ajax_forms.js');
       //    }
           if (config('javascript.js_window.js_validation')) {
               $_class = " jsvalid";
               /** @var JavascriptValidator $valid */
               $valid = \JsValidator::make($form->Rules, $form->RuleMessages, [], '#' . $rand_id);
               $valid->ignore('.novalidate');
       //        Debugbar::info($form->Rules);
       //        Admin::js([
       //            '/vendor/jsvalidation/js/custom.js',
       //            '/vendor/jsvalidation/js/jsvalidation.min.js'
       //        ]);
               Admin::script((string)$valid);
           }
           if (config('javascript.js_window.ajax_form')) {
               $_class .= " ajaxform ";
           }
           //js add in bootstrap
    @endphp

    @if($form->hasRows())
        {!! $form->open(['class'=> $_class, 'id'=> $rand_id]) !!}
    @else
        {!! $form->open(['class' => "form-horizontal" . $_class,'id'=> $rand_id]) !!}
    @endif
    <div class="box-body">

        @if(!$tabObj->isEmpty())
            @include('admin::form.tab', compact('tabObj'))
        @else
            <div class="fields-group">

                @if($form->hasRows())
                    @foreach($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    @foreach($form->fields() as $field)
                        {!! $field->render() !!}
                    @endforeach
                @endif


            </div>
        @endif

    </div>
    <!-- /.box-body -->
    <div class="box-footer">

        @if( ! $form->isMode(\Encore\Admin\Form\Builder::MODE_VIEW)  || ! $form->option('enableSubmit'))
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @endif
        <div class="{{$width['label']}}">

        </div>
        <div class="{{$width['field']}}">

            {!! $form->submitButton() !!}

            {!! $form->resetButton() !!}

        </div>

    </div>

    @foreach($form->getHiddenFields() as $hiddenField)
        {!! $hiddenField->render() !!}
    @endforeach
    <script data-exec-on-popstate>
        $(function () {
            {{--@foreach(\Encore\Admin\Admin::$script as $s)--}}
            {{--{!! $s !!}--}}
            {{--@endforeach--}}
        });
    </script>
<!-- /.box-footer -->
    {!! $form->close() !!}



</div>

