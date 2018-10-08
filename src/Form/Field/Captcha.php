<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form;

class Captcha extends Text
{
    protected $rules = 'required|captcha';

    protected $view = 'admin::form.captcha';

    public function __construct($column, $arguments = [])
    {
        if (!class_exists(\Mews\Captcha\Captcha::class)) {
            throw new \Exception('To use captcha field, please install [mews/captcha] first.');
        }

        $this->column = $column;
        $this->label = array_get($arguments, 0, trans('admin::lang.captcha'));
    }

    public function setForm(Form $form = null)
    {
        $this->form = $form;

        $this->form->ignore($this->column);

        return $this;
    }

    public function render()
    {
        $this->script = <<<EOT

$('#{$this->column}-captcha').on('click', function () {
    $(this).attr('src', $(this).attr('src')+'?'+Math.random());
});

EOT;

        return parent::render();
    }
}
