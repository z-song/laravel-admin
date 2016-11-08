<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu.
 *
 * @property int $id
 *
 * @method where($parent_id, $id)
 */
class Menu extends Model
{
    /**
     * @var array
     */
    protected static $branchOrder = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'order', 'title', 'icon', 'uri'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('admin.database.menu_table');

        parent::__construct($attributes);
    }

    /**
     * A Menu belongs to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $pivotTable = config('admin.database.role_menu_table');

        return $this->belongsToMany(Role::class, $pivotTable, 'menu_id', 'role_id');
    }

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
            $elements = static::with('roles')->orderByRaw('`order` = 0,`order`')->get()->toArray();
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
     * Save a tree from a tree like array.
     *
     * @param array $tree
     * @param int   $parentId
     */
    public static function saveTree($tree = [], $parentId = 0)
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
                static::saveTree($branch['children'], $branch['id']);
            }
        }
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
    public static function buildSelectOptions(array $elements = [], $parentId = 0, $prefix = '')
    {
        $prefix = $prefix ?: str_repeat('&nbsp;', 6);

        $options = [];

        if (empty($elements)) {
            $elements = static::orderByRaw('`order` = 0,`order`')->get(['id', 'parent_id', 'title'])->toArray();
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
     * Delete current item and its children.
     *
     * @throws \Exception
     *
     * @return bool|null
     */
    public function delete()
    {
        $this->where('parent_id', $this->id)->delete();

        return parent::delete();
    }
}
