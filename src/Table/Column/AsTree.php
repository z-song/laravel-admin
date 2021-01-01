<?php

namespace Encore\Admin\Table\Column;

use Encore\Admin\Table\Column;
use Encore\Admin\Table\Displayers\TreeDisplay;
use Illuminate\Database\Eloquent\Collection;

/**
 * @mixin Column
 */
trait AsTree
{
    public function buildTree()
    {
        $eloquent = $this->table->model()->getOriginalModel();

        $column = $eloquent->getParentColumn();

        $this->table->model()->where($column, 0);

        $this->table->model()->collection(function (Collection $collection) use ($column) {
            $children = $this->getTreeChildren($collection->map->getKey()->toArray(), $column);

            if ($children->isEmpty()) {
                return $collection;
            }

            $collection = $collection->merge($children)->keyBy->getKey();

            foreach ($collection as $item) {
                if ($parent = $collection->get($item->{$column})) {
                    $item->__space = ($parent->__space ?: 0) + 1;
                }
            }

            return new Collection(static::sortTree($collection));
        });

        $this->displayUsing(TreeDisplay::class);
    }

    protected function sortTree($nodes, $parent = 0, $level = 0)
    {
        $tree = [];

        foreach ($nodes as $node) {
            if ($node->getParentKey() == $parent) {
                $node->__space = $level + 1;

                $children = $this->sortTree($nodes, $node->getKey(), $node->__space);

                $tree[$node->getKey()] = $node;

                if ($children) {
                    $node->__has_children = true;
                    $tree += $children;
                }
            }
        }

        return $tree;
    }

    /**
     * @param array $keys
     *
     * @return Collection
     */
    protected function getTreeChildren($keys, $column)
    {
        $keys = implode(',', $keys);

        $model = $this->table->model()->getOriginalModel();

        $sql = <<<SQL
SELECT GROUP_CONCAT(lv SEPARATOR ',') as children FROM (
    SELECT @pv:=(SELECT GROUP_CONCAT({$model->getKeyName()} SEPARATOR ',') FROM `{$model->getTable()}`
    WHERE FIND_IN_SET({$column}, @pv)) AS lv FROM `{$model->getTable()}`
    JOIN
    (SELECT @pv:='{$keys}') tmp
) a;
SQL;

        $result = $model->getConnection()->select($sql);

        $childrenKeys = data_get($result, '0.children');

        return $model->find(explode(',', $childrenKeys));
    }
}
