
<div class="row">
    <div class="col-md-2"><h4 class="pull-right">{{ $label }}</h4></div>
    <div class="col-md-6"></div>
</div>

<hr style="margin-top: 0px;">

<style>
    .nav-tabs > li:hover > i{
        display: inline;
    }
    .close-tab {
        position: absolute;
        font-size: 10px;
        top: 2px;
        right: 5px;
        color: #94A6B0;
        cursor: pointer;
        display: none;
    }
</style>
<div id="has-many-{{$column}}" class="nav-tabs-custom has-many-{{$column}}">
    <ul class="nav nav-tabs">
        @foreach($forms as $pk => $form)
            <li class="@if ($form == reset($forms)) active @endif ">
                <a href="#{{ $form->getRelationName() . '_' . $pk }}" data-toggle="tab">{{ $pk }}</a>
                <i class="close-tab fa fa-times" ></i>
            </li>
        @endforeach
        <li class="pull-right nav-tools" style="height: 45px;">
            <button type="button" class="btn btn-success btn-sm add"><i class="fa fa-save"></i>&nbsp;New</button>
        </li>
    </ul>
    
    <div class="tab-content has-many-{{$column}}-forms">

        @foreach($forms as $pk => $form)
            <div class="tab-pane fields-group has-many-{{$column}}-form @if ($form == reset($forms)) active @endif" id="{{ $form->getRelationName() . '_' . $pk }}">
                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>
        @endforeach
    </div>

    <template class="{{$column}}-tpl">
        <div class="nav-tab-tpl">
            <li >
                <a href="#{{ $template->getRelationName() . '_tpl_' . $template::DEFAULT_KEY_NAME }}" data-toggle="tab">&nbsp;New {{ $template::DEFAULT_KEY_NAME }}</a>
                <i class="close-tab fa fa-times" ></i>
            </li>
        </div>
        <div class="pane-tpl">
            <div class="tab-pane fields-group new" id="{{ $template->getRelationName() . '_tpl_' . $template::DEFAULT_KEY_NAME }}">
                {!! $template->getTemplateHtml() !!}
            </div>
        </div>
    </template>


    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-6">
            <div class="add btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;{{ trans('admin::lang.new') }}</div>
        </div>
    </div>

</div>