
<div class="row">
    <div class="col-md-2"><h4 class="pull-right">{{ $label }}</h4></div>
    <div class="col-md-6"></div>
</div>

<hr style="margin-top: 0px;">

<div class="form-has-many">

    <div class="form-has-many-fields">

        @foreach($forms as $pk => $form)

            <div class="form-has-many-form">

                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach

                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                        <input type="hidden" value="0" name="{{ $form->getRelationName()."[old][$pk][_remove]" }}" class="item-to-remove"/>
                        <div class="remove btn btn-warning btn-sm pull-right" data-pk="{{ $pk }}"><i class="fa fa-trash">&nbsp;</i>Remove</div>
                    </div>
                </div>

                <hr>
            </div>

        @endforeach
    </div>

    <div class="form-has-many-template hide">
        <div class="form-has-many-form">

            @foreach($template->fields() as $field)
                {!! $field->render() !!}
            @endforeach
            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-6">
                    <input type="hidden" value="0" name="{{ $template->getRelationName()."[new][_remove][]" }}" class="item-to-remove"/>
                    <div class="remove btn btn-warning btn-sm pull-right"><i class="fa fa-trash"></i>&nbsp;Remove</div>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-6">
            <div class="add btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;New</div>
        </div>
    </div>

</div>