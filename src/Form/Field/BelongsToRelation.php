<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Table\Selectable;

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
     * BelongsToRelation constructor.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->setSelectable($arguments[0]);

        parent::__construct($column, array_slice($arguments, 1));
    }

    /**
     * @param string $selectable
     */
    protected function setSelectable($selectable)
    {
        if (!class_exists($selectable) || !is_subclass_of($selectable, Selectable::class)) {
            throw new \InvalidArgumentException(
                "[Class [{$selectable}] must be a sub class of Encore\Admin\Table\Selectable"
            );
        }

        $this->selectable = $selectable;
    }

    /**
     * @return string
     */
    public function getSelectable()
    {
        return $this->selectable;
    }

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl($multiple = 0)
    {
        $selectable = str_replace('\\', '_', $this->selectable);
        $args = [$multiple];

        return route('admin.handle-selectable', compact('selectable', 'args'));
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
        $style = <<<'STYLE'
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

.belongsto.modal .table-table .empty-table {
    padding: 20px !important;
}

.belongsto.modal .table-table .empty-table svg {
    width: 60px !important;
    height: 60px !important;
}

.belongsto.modal .table-box .box-footer {
    border-top: none !important;
}
STYLE;

        Admin::style($style);

        return $this;
    }

    /**
     * @return \Encore\Admin\Table
     */
    protected function makeTable()
    {
        /** @var Selectable $selectable */
        $selectable = new $this->selectable();

        return $selectable->renderFormTable($this->value());
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->modalID = sprintf('modal-selector-%s', $this->getElementClassString());

        $this->addScript()->addHtml()->addStyle();

        $this->addVariables([
            'table'    => $this->makeTable(),
            'options' => $this->getOptions(),
        ]);

        $this->addCascadeScript();

        return parent::fieldRender();
    }
}
