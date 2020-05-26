<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Selectable;

trait BelongsToRelation
{
    /**
     * @var string
     */
    protected $modalID;

    /**
     * @var string
     */
    protected $selectable;

    /**
     * @var string
     */
    protected $columnName = '';

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl($multiple = 0)
    {
        $selectable = str_replace('\\', '_', $this->selectable);

        return route('admin.handle-selectable', compact('selectable', 'multiple'));
    }

    /**
     * @return $this
     */
    public function addHtml()
    {
        $trans = [
            'choose' => admin_trans('admin.choose'),
            'cancal' => admin_trans('admin.cancel'),
            'submit' => admin_trans('admin.submit'),
        ];

        $html = <<<HTML
<div class="modal fade belongsto" id="{$this->modalID}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 5px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">{$trans['choose']}</h4>
      </div>
      <div class="modal-body">
        <div class="loading text-center">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{$trans['cancal']}</button>
        <button type="button" class="btn btn-primary submit">{$trans['submit']}</button>
      </div>
    </div>
  </div>
</div>
HTML;
        Admin::html($html);

        return $this;
    }

    /**
     * @return $this
     */
    public function addStyle()
    {
        $style = <<<STYLE
.belongsto.modal tr {
    cursor: pointer;
}

.belongsto.modal .box {
    border-top: none;
    margin-bottom: 0;
    box-shadow: none;
}
.belongsto.modal .loading {
    margin: 50px;
}
STYLE;

        Admin::style($style);

        return $this;
    }

    /**
     * @param string $selectable
     * @param string $column
     *
     * @return string
     */
    public function display($selectable = null, $column = '')
    {
        if (!class_exists($selectable) || !is_subclass_of($selectable, Selectable::class)) {
            throw new \InvalidArgumentException(
                "[Class [{$selectable}] must be a sub class of Encore\Admin\Grid\Selectable"
            );
        }

        $this->columnName = $column ?: $this->getName();
        $this->selectable = $selectable;
        $this->modalID = sprintf('modal-grid-selector-%s', $this->getClassName());

        $this->addHtml()->addScript()->addStyle();

        return <<<HTML
<span class="grid-selector" data-toggle="modal" data-target="#{$this->modalID}" key="{$this->getKey()}" data-val="{$this->getOriginalData()}">
   <a href="javascript:void(0)" class="text-muted">
       <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;
       <span class="text">{$this->value}</span>
   </a>
</span>
HTML;
    }
}
