<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Selectable;

class BelongsTo extends AbstractDisplayer
{
    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl($selectable, $multiple = 0)
    {
        $selectable = str_replace('\\', '_', $selectable);
        $args = [$multiple];

        return route('admin.handle-selectable', compact('selectable', 'args'));
    }

    /**
     * @return mixed
     */
    protected function getOriginalData()
    {
        return $this->getColumn()->getOriginal();
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

        return Admin::component('admin::grid.inline-edit.belongsto', [
            'modal'     => sprintf('modal-grid-selector-%s', $this->getClassName()),
            'key'       => $this->getKey(),
            'original'  => $this->getOriginalData(),
            'value'     => $this->getValue(),
            'resource'  => $this->getResource(),
            'name'      => $column ?: $this->getName(),
            'relation'  => get_called_class(),
            'url'       => $this->getLoadUrl($selectable, get_called_class() == BelongsToMany::class),
        ]);
    }
}
