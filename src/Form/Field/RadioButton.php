<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;

class RadioButton extends Radio
{
    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    protected function addScript()
    {
        $script = <<<'SCRIPT'
    //设置radio button选中的样式
    $('.radio-group-toggle label').filter('.active').attr('class','btn btn-primary active');
    //radio button点击事件监测
    $('.radio-group-toggle label').click(function() {
      $(this).attr('class','btn btn-primary active').siblings().attr('class','btn btn-default');
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
