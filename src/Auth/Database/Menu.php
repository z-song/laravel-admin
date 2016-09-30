<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    public function toTree(array $elements = [], $parentId = 0)
    {
        $branch = [];

        if (empty($elements)) {
            $elements = $this->all()->toArray();

            if (empty($elements)) {
                return [];
            }
        }

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->toTree($elements, $element['id']);

                if ($children) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }

    public function buildTree($tree = [], $parentId = 0)
    {
        foreach ($tree as $branch) {
            $node = static::find($branch['id']);

            $node->parent_id = $parentId;
            $node->save();

            if (isset($branch['children'])) {
                $this->buildTree($branch['children'], $branch['id']);
            }
        }
    }
}
