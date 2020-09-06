@php(\Illuminate\Support\Arr::forget($group_attrs, 'class'))

<div class="row has-many-table form-group" {!! admin_attrs($group_attrs) !!}>
    <div class="{{$viewClass['label']}}"><label class="float-right">{{ $label }}</label></div>
    <div class="{{$viewClass['field']}}">
        <div id="has-many-{{$column}}">
            <table class="table table-has-many has-many-{{$column}}">
                <thead>
                <tr>
                    @foreach($headers as $header)
                        <th class="border-top-0 pt-0">{{ $header }}</th>
                    @endforeach

                    <th class="d-none"></th>

                    @if($options['allowDelete'])
                        <th class="border-top-0"></th>
                    @endif
                </tr>
                </thead>
                <tbody class="has-many-{{$column}}-forms">
                @foreach($forms as $pk => $form)
                    <tr class="has-many-{{$column}}-form fields-group">

                        <?php $hidden = ''; ?>

                        @foreach($form->fields() as $field)
                            @if (is_a($field, \Encore\Admin\Form\Field\Hidden::class))
                                <?php $hidden .= $field->render(); ?>
                                @continue
                            @endif

                            <td>{!! $field->setLabelClass(['d-none'])->setWidth(12, 0)->render() !!}</td>
                        @endforeach

                        <td class="d-none">{!! $hidden !!}</td>

                        @if($options['allowDelete'])
                            <td>
                                <span class="remove btn btn-danger float-right text-white"><i class="fas fa-trash-alt"></i></span>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

            <template class="{{$column}}-tpl">
                <tr class="has-many-{{$column}}-form fields-group">

                    {!! $template !!}

                    <td>
                        <span class="remove btn btn-warning btn-sm float-right"><i class="fas fa-trash-alt"></i></span>
                    </td>
                </tr>
            </template>

            @if($options['allowCreate'])
                <div>
                    <div class="{{$viewClass['field']}}">
                        <div class="add btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;{{ admin_trans('admin.new') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<hr style="margin-top: 0px;">

<script>
    var index = 0;
    $('#has-many-{{ $column }}').on('click', '.add', function () {
        var tpl = $('template.{{ $column }}-tpl');
        index++;
        var template = tpl.html().replace(/{{ \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}/g, index);
        $('.has-many-{{ $column }}-forms').append(template);
        return false;
    });

    $('#has-many-{{ $column }}').on('click', '.remove', function () {
        var $form = $(this).closest('.has-many-{{ $column }}-form');
        var first_input_name = $form.find('input[name]:first').attr('name');
        if (first_input_name.match('{{ $column }}\\\[new_')) {
            $form.remove();
        } else {
            $form.hide();
            $form.find('.{{ \Encore\Admin\Form\NestedForm::REMOVE_FLAG_CLASS }}').val(1);
            $form.find('input').removeAttr('required');
        }
        return false;
    });
</script>

