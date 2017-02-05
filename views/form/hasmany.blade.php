
<div class="row">
    <div class="col-md-{{$width['label']}}"><h4 class="pull-right">{{ $label }}</h4></div>
    <div class="col-md-{{$width['field']}}"></div>
</div>

<hr style="margin-top: 0px;">

<div id="has-many-{{$column}}" class="has-many-{{$column}}">

    <div class="has-many-{{$column}}-forms">

        @foreach($forms as $pk => $form)

            <div class="has-many-{{$column}}-form fields-group">

                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach

                <div class="form-group">
                    <label class="col-sm-{{$width['label']}} control-label"></label>
                    <div class="col-sm-{{$width['field']}}">
                        <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash">&nbsp;</i>{{ trans('admin::lang.remove') }}</div>
                    </div>
                </div>

                <hr>
            </div>

        @endforeach
    </div>

    <template class="{{$column}}-tpl">
        <div class="has-many-{{$column}}-form">

            {!! $template !!}

            <div class="form-group">
                <label class="col-sm-{{$width['label']}} control-label"></label>
                <div class="col-sm-{{$width['field']}}">
                    <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash"></i>&nbsp;{{ trans('admin::lang.remove') }}</div>
                </div>
            </div>
            <hr>
        </div>
    </template>

    <div class="form-group">
        <label class="col-sm-{{$width['label']}} control-label"></label>
        <div class="col-sm-{{$width['field']}}">
            <div class="add btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;{{ trans('admin::lang.new') }}</div>
        </div>
    </div>

</div>