<?php

namespace Encore\Admin\Actions;

use Illuminate\Http\Request;

abstract class BatchAction extends GridAction
{
    /**
     * @var string
     */
    public $selectorPrefix = '.grid-batch-action-';

    /**
     * variable holding additional CSS classes for the button
     *
     * @var array $cssClasses
     */
    private $cssClasses = [];

    /**
     * add a single CSS class string to the CSS-Classes array
     *
     * @param string $cssClass
     *
     * @return $this
     */
    public function addCssClass(string $cssClass)
    {
        if (empty($cssClass)) {
            return $this;
        }
        if (!is_string($cssClass)) {
            throw new \Exception(__METHOD__.': item is not a valid string');
        }
        $this->cssClasses[] = $cssClass;

        return $this;
    }

    /**
     * add multiple CSS class strings to the CSS-Classes array
     *
     * @param array $cssClasses
     *
     * @return $this
     */
    public function addCssClasses(array $cssClasses)
    {
        if (empty($cssClasses)) {
            return $this;
        }
        if (!is_array($cssClasses)) {
            throw new \Exception(__METHOD__.': parameter is not a valid array');
        }
        foreach ($cssClasses as $item) {
            if (!is_string($item)) {
                throw new \Exception(__METHOD__.': item is not a valid string');
            }
            $this->cssClasses[] = $item;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function actionScript()
    {
        $warning = __('No data selected!');

        return <<<SCRIPT
        var key = $.admin.grid.selected();

        if (key.length === 0) {
            $.admin.toastr.warning('{$warning}', '', {positionClass: 'toast-top-center'});
            return ;
        }

        Object.assign(data, {_key:key});
SCRIPT;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function retrieveModel(Request $request)
    {
        if (!$key = $request->get('_key')) {
            return false;
        }

        $modelClass = str_replace('_', '\\', $request->get('_model'));

        if (is_string($key)) {
            $key = explode(',', $key);
        }

        if ($this->modelUseSoftDeletes($modelClass)) {
            return $modelClass::withTrashed()->findOrFail($key);
        }

        return $modelClass::findOrFail($key);
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->addScript();

        $modalId = '';

        if ($this->interactor instanceof Interactor\Form) {
            $modalId = $this->interactor->getModalId();

            if ($content = $this->html()) {
                return $this->interactor->addElementAttr($content, $this->selector);
            }
        }

        return sprintf(
            "<a href='javascript:void(0);' class='%s %s' %s>%s</a>",
            $this->getElementClass(),
            (!empty($this->cssClasses)) ? implode(' ', $this->cssClasses) : '',
            $modalId ? "modal='{$modalId}'" : '',
            $this->name()
        );
    }
}
