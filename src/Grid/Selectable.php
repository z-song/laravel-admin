<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Grid;
use Encore\Admin\Grid\Selectable\Checkbox;
use Encore\Admin\Grid\Selectable\Radio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin Grid
 */
abstract class Selectable
{
    /**
     * @var string
     */
    public $model;

    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var string
     */
    protected $key = '';

    /**
     * @var int
     */
    protected $perPage = 10;

    /**
     * @return Grid
     */
    abstract public function make();

    /**
     * @param bool $multiple
     * @return string
     */
    public function render($multiple = false)
    {
        $this->make();

        $this->appendRemoveBtn();

        $this->paginate($this->perPage)
            ->expandFilter()
            ->disableExport()
            ->disableActions()
            ->disableBatchActions()
            ->disableCreateButton()
            ->disableColumnSelector()
            ->disablePerPageSelector();

        $displayer = $multiple ? Checkbox::class : Radio::class;

        $this->prependColumn('__modal_selector__', ' ')
            ->displayUsing($displayer, [$this->key]);

        return $this->grid->render();
    }

    public function renderFormGrid($values, $multiple = false)
    {
        $this->make();

        $this->appendRemoveBtn(false);

        $this->model()->whereKey(Arr::wrap($values));

        $this->disableFilter()
            ->disableExport()
            ->disableActions()
            ->disableBatchActions()
            ->disableCreateButton()
            ->disableColumnSelector()
            ->disablePerPageSelector()
        ;

        if (!$multiple) {
            $this->disablePagination();
        }

        $this->tools(function (Tools $tools) {
            $tools->append(new Grid\Selectable\BrowserBtn());
        });

        return $this->grid;
    }

    protected function appendRemoveBtn($hide = true)
    {
        $hide = $hide ? 'hide' : '';
        $key = $this->key;

        $this->column('__remove__', ' ')->display(function () use ($hide, $key) {
            return <<<BTN
<a href="javascript:void(0);" class="grid-row-remove {$hide}" data-key="{$this->getAttribute($key)}">
    <i class="fa fa-trash"></i>
</a>
BTN;
        });
    }

    protected function initGrid()
    {
        if (!class_exists($this->model) || !is_subclass_of($this->model, Model::class)) {
            throw new \InvalidArgumentException("Invalid model [{$this->model}]");
        }

        /** @var Model $model */
        $model = new $this->model;

        $this->grid = new Grid(new $model);

        if (!$this->key) {
            $this->key = $model->getKeyName();
        }
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments = [])
    {
        if (is_null($this->grid)) {
            $this->initGrid();
        }

        return $this->grid->{$method}(...$arguments);
    }
}
