<?php

namespace Encore\Admin\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

trait ModelTree
{
    /**
     * @var array
     */
    protected static $branchOrder = [];

    /**
     * Format data to tree like array.
     *
     * @param array $elements
     * @param int   $parentId
     *
     * @return array
     */
    public static function toTree(array $elements = [], $parentId = 0)
    {
        $branch = [];

        if (empty($elements)) {
            $elements = static::allElements();
        }

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = static::toTree($elements, $element['id']);

                if ($children) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * @return mixed
     */
    public static function allElements()
    {
        $orderColumn = DB::getQueryGrammar()->wrap('order');
        $byOrder = $orderColumn.' = 0,'.$orderColumn;

        return static::orderByRaw($byOrder)->get()->toArray();
    }

    /**
     * Set the order of branches in the tree.
     *
     * @param array $order
     *
     * @return void
     */
    protected static function setBranchOrder(array $order)
    {
        static::$branchOrder = array_flip(array_flatten($order));

        static::$branchOrder = array_map(function ($item) {
            return ++$item;
        }, static::$branchOrder);
    }

    /**
     * Save tree order from a tree like array.
     *
     * @param array $tree
     * @param int   $parentId
     */
    public static function saveOrder($tree = [], $parentId = 0)
    {
        if (empty(static::$branchOrder)) {
            static::setBranchOrder($tree);
        }

        foreach ($tree as $branch) {
            $node = static::find($branch['id']);

            $node->parent_id = $parentId;
            $node->order = static::$branchOrder[$branch['id']];
            $node->save();

            if (isset($branch['children'])) {
                static::saveOrder($branch['children'], $branch['id']);
            }
        }
    }

    /**
     * Get options for Select field in form.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function selectOptions()
    {
        $options = static::buildSelectOptions();

        return collect($options)->prepend('Root', 0)->all();
    }

    /**
     * Build options of select field in form.
     *
     * @param array  $elements
     * @param int    $parentId
     * @param string $prefix
     *
     * @return array
     */
    protected static function buildSelectOptions(array $elements = [], $parentId = 0, $prefix = '')
    {
        $prefix = $prefix ?: str_repeat('&nbsp;', 6);

        $options = [];

        if (empty($elements)) {
            $orderColumn = DB::getQueryGrammar()->wrap('order');
            $byOrder = $orderColumn.' = 0,'.$orderColumn;
            $elements = static::orderByRaw($byOrder)->get(['id', 'parent_id', 'title'])->toArray();
        }

        foreach ($elements as $element) {
            $element['title'] = $prefix.'&nbsp;'.$element['title'];
            if ($element['parent_id'] == $parentId) {
                $children = static::buildSelectOptions($elements, $element['id'], $prefix.$prefix);

                $options[$element['id']] = $element['title'];

                if ($children) {
                    $options += $children;
                }
            }
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $this->where('parent_id', $this->id)->delete();

        return parent::delete();
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Model $branch) {

            if (Request::has('parent_id') && Request::input('parent_id') == $branch->getKey()) {
                throw new \Exception(trans('admin::lang.parent_select_error'));
            }

            if (Request::has('_order')) {
                $order = Request::input('_order');

                Request::offsetUnset('_order');

                static::tree()->saveOrder($order);

                return false;
            }

            return $branch;
        });
    }
}
