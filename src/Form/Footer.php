<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Renderable;

class Footer implements Renderable
{
    /**
     * Footer view.
     *
     * @var string
     */
    protected $view = 'admin::form.footer';

    /**
     * Form builder instance.
     *
     * @var Builder
     */
    protected $builder;

    /**
     * Available buttons.
     *
     * @var array
     */
    protected $buttons = ['reset', 'submit'];

    /**
     * Available checkboxes.
     *
     * @var array
     */
    protected $checkboxes = ['view', 'continue_editing'];

    /**
     * Footer constructor.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Disable reset button.
     *
     * @return $this
     */
    public function disableReset()
    {
        array_delete($this->buttons, 'reset');

        return $this;
    }

    /**
     * Disable submit button.
     *
     * @return $this
     */
    public function disableSubmit()
    {
        array_delete($this->buttons, 'submit');

        return $this;
    }

    /**
     * Disable View Checkbox.
     *
     * @return $this
     */
    public function disableViewCheck()
    {
        array_delete($this->checkboxes, 'view');

        return $this;
    }

    /**
     * Disable Editing Checkbox.
     *
     * @return $this
     */
    public function disableEditingCheck()
    {
        array_delete($this->checkboxes, 'continue_editing');

        return $this;
    }

    /**
     * Setup scripts.
     */
    protected function setupScript()
    {
        $script = <<<'EOT'
$('.after-submit').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChecked', function () {
    $('.after-submit').not(this).iCheck('uncheck');
});
EOT;

        Admin::script($script);
    }

    /**
     * Render footer.
     *
     * @return string
     */
    public function render()
    {
        $this->setupScript();

        $data = [
            'buttons'   => $this->buttons,
            'checkboxes'   => $this->checkboxes,
            'width'     => $this->builder->getWidth(),
        ];

        return view($this->view, $data)->render();
    }
}
