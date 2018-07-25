<div id="form_has_{{$column}}">
    <div class="row">
        <div class="{{$viewClass['label']}}"><h4 class="pull-right">{{ $label }}</h4></div>
        <div class="{{$viewClass['field']}}"></div>
    </div>

    <hr style="margin-top: 0px;">

    <div id="has-many-{{$column}}" class="has-many-{{$column}} dd">

        <ol class="has-many-{{$column}}-forms dd-list">

            @foreach($forms as $pk => $form)

                <li class="has-many-{{$column}}-form fields-group dd-item dd-handle">

                    @foreach($form->fields() as $field)
                        {!! $field->render() !!}
                    @endforeach

                    <div class="form-group ">
                        <label class="{{$viewClass['label']}} control-label"></label>
                        <div class="{{$viewClass['field']}}">
                            <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                        </div>
                    </div>

                    <hr>
                </li>

            @endforeach
        </ol>

        <template class="{{$column}}-tpl">
            <li class="has-many-{{$column}}-form fields-group dd-item dd-handle">

                {!! $template !!}

                <div class="form-group ">
                    <label class="{{$viewClass['label']}} control-label"></label>
                    <div class="{{$viewClass['field']}}">
                        <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash"></i>&nbsp;{{ trans('admin.remove') }}</div>
                    </div>
                </div>
                <hr>
            </li>
        </template>

        <div class="form-group">
            <label class="{{$viewClass['label']}} control-label"></label>
            <div class="{{$viewClass['field']}}">
                <div class="add btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;{{ trans('admin.new') }}</div>
            </div>
        </div>

    </div>
</div>




<script type="text/javascript">  
    jQuery(function() {  
        $('.dd').nestable({maxDepth:1});
        //$('#form_has_{{$column}}').nestable({maxDepth:1,rootClass:'form_has_{{$column}}',listClass:'has-many-{{$column}}-forms',itemClass:'has-many-{{$column}}-form'});
    });  
</script>
