@php(\Illuminate\Support\Arr::forget($group_attrs, 'class'))
<style>
    .close-{{ $column }}-tab {
        position: relative;
        top: -10px;
        right: -10px;
    }
</style>
<div id="has-many-{{$column}}" class="nav-tabs-custom has-many-{{$column}} form-group" {!! admin_attrs($group_attrs) !!}>
    <div class="row header">
        <div class="{{$viewClass['label']}}"><label class="float-right">{{ $label }}</label></div>
        <div class="{{$viewClass['field']}}">
            <ul class="nav nav-tabs" role="tablist">
                @foreach($forms as $pk => $form)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $loop->index == 0 ? 'active' : '' }}"
                           id="tab-{{ ($relationName ? $relationName . '-' : '') . $pk }}"
                           href="#nav-{{ ($relationName ? $relationName . '-' : '') . $pk }}"
                           aria-controls="nav-{{ ($relationName ? $relationName . '-' : '') . $pk }}"
                           data-toggle="tab"
                           role="tab"
                           aria-selected="true">
                            {{ $label . ' ' . $pk }}<span class="close-{{$column}}-tab text-danger d-none"><i class="fas fa-times"></i></span>
                        </a>
                    </li>
                @endforeach
                <a href="javascript:void(0);" class="btn btn-default btn-sm align-self-center ml-2 add-{{$column}}-tab"><i class="fas fa-plus-circle"></i></a>
            </ul>

            <div class="tab-content has-many-{{$column}}-forms py-3">
                @foreach($forms as $pk => $form)
                    <div class="tab-pane fields-group has-many-{{$column}}-form {{ $form == reset($forms) ? 'active' : '' }}" id="nav-{{ ($relationName ? $relationName . '-' : '') . $pk }}" aria-labelledby="tab-{{ ($relationName ? $relationName . '-' : '') . $pk }}" role="tabpanel">
                        @foreach($form->fields() as $field)
                            {!! $field->render() !!}
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <template class="nav-tab-tpl">
        <li class="nav-item" role="presentation">
            <a class="nav-link"
               id="tab-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
               href="#nav-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
               aria-controls="nav-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
               data-toggle="tab"
               role="tab"
               aria-selected="true">
                New {{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}<span class="close-{{$column}}-tab text-danger d-none"><i class="fas fa-times"></i></span>
            </a>
        </li>
    </template>
    <template class="pane-tpl">
        <div class="tab-pane fields-group new"
             id="nav-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
             aria-labelledby="tab-{{ ($relationName ? $relationName . '-' : '') . 'new-' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}"
             role="tabpanel">
            {!! $template !!}
        </div>
    </template>
</div>

<script>
    showCloseTab();

    var index = 0;
    $('#has-many-{{ $column }} .nav')
    // 新增
        .on('click', '.add-{{$column}}-tab', function () {
            index++;
            var default_key_name = '{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}';
            var reg = new RegExp(default_key_name, "g");
            var navTabHtml = $('#has-many-{{ $column }} > template.nav-tab-tpl').html().replace(reg, index);
            var paneHtml = $('#has-many-{{ $column }} > template.pane-tpl').html().replace(reg, index);
            $(this).before(navTabHtml);
            $('#has-many-{{ $column }} .row .tab-content').append(paneHtml);
            $(this).prev().find('a').tab('show');
            showCloseTab();
        })
        // 关闭
        .on('click', '.close-{{$column}}-tab', function () {
            var navTab = $(this).parent().parent();
            var pane = $(navTab.find('a').attr('href'));

            if (pane.hasClass('new')) {
                pane.remove();
            } else {
                pane.removeClass('active').find('.{{ \Encore\Admin\Form\NestedForm::REMOVE_FLAG_CLASS }}').val(1);
            }

            navTab.remove();
            $('#has-many-{{ $column }} .nav > li:last a').tab('show');
            showCloseTab();
        });

    // 切换时
    $('body').on('shown.bs.tab', 'a[data-toggle="tab"]', function () {
        showCloseTab();
    });
    // 显示关闭按钮
    function showCloseTab() {
        $('#has-many-{{ $column }} .nav .nav-link .close-{{ $column }}-tab').addClass('d-none');
        $('#has-many-{{ $column }} .nav .nav-link.active .close-{{ $column }}-tab').removeClass('d-none');
    }
</script>
