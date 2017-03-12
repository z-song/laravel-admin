<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class Menu.
 *
 * @property int $id
 *
 * @method where($parent_id, $id)
 */
class Menu extends Model
{
    use ModelTree, AdminBuilder;

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
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.menu_table'));

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

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'menu_id', 'role_id');
    }


    /**
     * @return array
     */
    public function allNodes()
    {
        $orderColumn = DB::getQueryGrammar()->wrap($this->orderColumn);
        $byOrder = $orderColumn.' = 0,'.$orderColumn;
        if (config("admin.auto_menu")) {
            //菜单不跟角色挂钩,只有一份菜单
            //每个人能看到的菜单,由其拥有的权限决定
            //如果是管理员,返回所有菜单;如果是其他账号,返回相应菜单
            if (Auth::guard("admin")->user()->isAdministrator()) {
                return static::orderByRaw($byOrder)->get()->toArray();
            } else {
                $permissionSlugArr = array_pluck(Auth::guard("admin")->user()->allPermissionArr(), 'slug');
                $parent_ids = static::whereIn('uri', $permissionSlugArr)->get()->pluck("parent_id");

                //查出来的菜单如果有父菜单也要返回
                $result = static::whereIn('uri', $permissionSlugArr)
                    ->orWhereIn('id', $parent_ids)
                    ->orderByRaw($byOrder)->get()->toArray();

                return $result;
            }
        } else {
            return static::with('roles')->orderByRaw($byOrder)->get()->toArray();
        }


    }
}
