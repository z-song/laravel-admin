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
        @foreach($groups as $pk => $group)
            <li class="@if ($group == reset($groups)) active @endif ">
                <a href="#{{ $group->getRelationName() . '_' . $pk }}" data-toggle="tab">{{ $pk }}</a>
                <i class="close-tab fa fa-times" ></i>
            </li>
        @endforeach
        <li class="pull-right nav-tools">
            <button type="button" class="btn btn-success btn-sm add"><i class="fa fa-save"></i>&nbsp;New</button>
        </li>
    </ul>
    <div class="tab-content">
        @foreach($groups as $pk => $group)
        <div class="tab-pane @if ($group == reset($groups)) active @endif" id="{{ $group->getRelationName() . '_' . $pk }}">
            @foreach($group->fields() as $field)
                {!! $field->render() !!}
            @endforeach
        </div>
        @endforeach
    </div>

    <template class="nav-tab-tpl">
        <li >
            <a href="#{{ $group->getRelationName() . '_tpl_' . $group::DEFAULT_KEY_NAME }}" data-toggle="tab">&nbsp;New</a>
            <i class="close-tab fa fa-times" ></i>
        </li>
    </template>
    <template class="pane-tpl">
        <div class="tab-pane" id="{{ $group->getRelationName() . '_tpl_' . $group::DEFAULT_KEY_NAME }}">
            {!! $template !!}
        </div>
    </template>
</div>