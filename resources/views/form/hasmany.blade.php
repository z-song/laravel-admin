@php(\Illuminate\Support\Arr::forget($group_attrs, 'class'))

<div class="row">
    <div class="{{$viewClass['label']}}">
        <label class="float-right">{{ $label }}</label>
    </div>
    <div class="{{$viewClass['field']}}"></div>
</div>

<hr class="pt-0">

<div id="has-many-{{$column}}" class="has-many-{{$column}} form-group" {!! admin_attrs($group_attrs) !!}>
    <div class="has-many-{{$column}}-forms">
        @foreach($forms as $pk => $form)
        <div class="has-many-{{$column}}-form fields-group" data-key="{{ $pk }}">
            @foreach($form->fields() as $field)
                {!! $field->render() !!}
            @endforeach
            @if($options['allowDelete'])
            <div class="form-group row">
                <label class="{{$viewClass['label']}}"></label>
                <div class="{{$viewClass['field']}}">
                    <div class="remove btn btn-warning btn-sm float-right">
                        <i class="fa fa-trash">&nbsp;</i>{{ admin_trans('admin.remove') }}
                    </div>
                </div>
            </div>
            @endif
            <hr>
        </div>
        @endforeach
    </div>

    <template class="{{$column}}-tpl">
        <div class="has-many-{{$column}}-form fields-group" data-key="new_{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">

            {!! $template !!}

            <div class="form-group row">
                <label class="{{$viewClass['label']}} col-form-label"></label>
                <div class="{{$viewClass['field']}}">
                    <div class="remove btn btn-warning btn-sm float-right">
                        <i class="fa fa-trash"></i>&nbsp;{{ admin_trans('admin.remove') }}
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </template>

    @if($options['allowCreate'])
    <div class="form-group row">
        <label class="{{$viewClass['label']}} col-form-label"></label>
        <div class="{{$viewClass['field']}}">
            <div class="add btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;{{ admin_trans('admin.new') }}</div>
        </div>
    </div>
    @endif
</div>

<script>
    var index = 0;
    $('#has-many-{{ $column }}').off('click', '.add').on('click', '.add', function () {
        var tpl = $('template.{{ $column }}-tpl');
        index++;
        var template = tpl.html().replace(/{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}/g, index);
        $('.has-many-{{ $column }}-forms').append(template);
        return false;
    });

    $('#has-many-{{ $column }}').off('click', '.remove').on('click', '.remove', function () {
        var $form = $(this).closest('.has-many-{{ $column }}-form');
        $form.find('input').removeAttr('required');
        $form.hide();
        $form.find('.{{ \Encore\Admin\Form\NestedForm::REMOVE_FLAG_CLASS }}').val(1);
        return false;
    });
</script>
