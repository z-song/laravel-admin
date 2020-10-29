@php(\Illuminate\Support\Arr::forget($group_attrs, 'class'))

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
<div id="has-many-{{$column}}" class="nav-tabs-custom has-many-{{$column}} form-group" {!! admin_attrs($group_attrs) !!}>
    <div class="row header">
        <div class="{{$viewClass['label']}}"><label class="float-right">{{ $label }}</label></div>
        <div class="{{$viewClass['field']}}">
            <button type="button" class="btn btn-default btn-sm add"><i class="fa fa-plus-circle" style="font-size: large;"></i></button>
        </div>
    </div>

    <div class="row my-2 pl-2">
        <ul class="nav nav-tabs col-10 offset-2">
            @foreach($forms as $pk => $form)
                <li class="nav-item">
                    <a href="#{{ $relationName . '_' . $pk }}" class="nav-link @if ($loop->index == 0) active @endif " data-toggle="tab">
                        {{ $pk }} <i class="fa fa-exclamation-circle text-red d-none"></i>
                    </a>
                    <i class="close-tab fa fa-times" ></i>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="tab-content has-many-{{$column}}-forms">
        @foreach($forms as $pk => $form)
            <div class="tab-pane fields-group has-many-{{$column}}-form @if ($form == reset($forms)) active @endif" id="{{ $relationName . '_' . $pk }}">
                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>
        @endforeach
    </div>

    <template class="nav-tab-tpl">
        <li class="new nav-item">
            <a href="#{{ $relationName . '_new_' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}" class="nav-link" data-toggle="tab">
                &nbsp;New {{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }} <i class="fa fa-exclamation-circle text-red d-none"></i>
            </a>
            <i class="close-tab fa fa-times" ></i>
        </li>
    </template>
    <template class="pane-tpl">
        <div class="tab-pane fields-group new" id="{{ $relationName . '_new_' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
            {!! $template !!}
        </div>
    </template>
</div>

<script>
    $('#has-many-{{ $column }} > .nav').off('click', 'i.close-tab').on('click', 'i.close-tab', function(){
        var $navTab = $(this).siblings('a');
        var $pane = $($navTab.attr('href'));
        if($pane.hasClass('new')){
            $pane.remove();
        }else{
            $pane.removeClass('active').find('.{{ \Encore\Admin\Form\NestedForm::REMOVE_FLAG_CLASS }}').val(1);
        }
        if($navTab.closest('li').hasClass('active')){
            $navTab.closest('li').remove();
            $('#has-many-{{ $column }} > .nav > li:nth-child(1) > a').tab('show');
        }else{
            $navTab.closest('li').remove();
        }
    });

    var index = 0;
    $('#has-many-{{ $column }} > .header').off('click', '.add').on('click', '.add', function(){
        index++;
        var navTabHtml = $('#has-many-{{ $column }} > template.nav-tab-tpl').html().replace(/{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}/g, index);
        var paneHtml = $('#has-many-{{ $column }} > template.pane-tpl').html().replace(/{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}/g, index);
        $('#has-many-{{ $column }} .row > .nav').append(navTabHtml);
        $('#has-many-{{ $column }} > .tab-content').append(paneHtml);
        $('#has-many-{{ $column }} .row > .nav > li:last-child a').tab('show');
    });

    if ($('.has-error').length) {
        $('.has-error').parent('.tab-pane').each(function () {
            var tabId = '#'+$(this).attr('id');
            $('li a[href="'+tabId+'"] i').removeClass('d-none');
        });

        var first = $('.has-error:first').parent().attr('id');
        $('li a[href="#'+first+'"]').tab('show');
    }
</script>
