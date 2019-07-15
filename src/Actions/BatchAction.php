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

        return $modelClass::findOrFail($key);
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->addScript();

        $content = $this->html();

        if ($content && $this->interactor instanceof Interactor\Form) {
            return $this->interactor->addElementAttr($content, $this->selector);
        }

        return sprintf(
            "<a href='javascript:void(0);' class='%s'>%s</a>",
            $this->getElementClass(),
            $this->name()
        );
    }
}