<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;

class CheckboxButton extends Checkbox
{
    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    protected function addScript()
    {
        $script = <<<'SCRIPT'
//设置checkbox button样式
$('.checkbox-group-toggle label').filter('.active').attr('class','btn btn-primary active');
$('.checkbox-group-toggle label').click(function(e) {
    e.stopPropagation();
    e.preventDefault();

    if ($(this).hasClass('active')) {
        $(this).attr('class','btn btn-default');
        $(this).find('input').prop('checked', false);
    } else {
        $(this).attr('class','btn btn-primary active');
        $(this).find('input').prop('checked', true);
    }

    $(this).find('input').trigger('change');
});
SCRIPT;

        Admin::script($script);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addScript();

        $this->addCascadeScript();

        $this->addVariables([
            'options' => $this->options,
            'checked' => $this->checked,
        ]);

        return parent::fieldRender();
    }
}
