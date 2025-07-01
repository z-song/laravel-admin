<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    use DefaultDatetimeFormat;
    use HasFactory;
    use ModelTree {
        ModelTree::boot as treeBoot;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'order', 'title', 'icon', 'uri', 'permission'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'parent_id' => 'integer',
        'order' => 'integer',
    ];

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
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        $pivotTable = config('admin.database.role_menu_table');

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'menu_id', 'role_id');
    }

    /**
     * @return array
     */
    public function allNodes(): array
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $orderColumn = DB::connection($connection)->getQueryGrammar()->wrap($this->orderColumn);

        $byOrder = 'ROOT ASC,'.$orderColumn;

        $query = static::query();

        if (config('admin.check_menu_roles') !== false) {
            $query->with('roles');
        }

        return $query->selectRaw('*, '.$orderColumn.' ROOT')->orderByRaw($byOrder)->get()->toArray();
    }

    /**
     * Laravel 11 Modern Attribute: Full URL for the menu item
     *
     * @return Attribute
     */
    protected function fullUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->uri)) {
                    return '#';
                }
                
                if (filter_var($this->uri, FILTER_VALIDATE_URL)) {
                    return $this->uri;
                }
                
                return admin_url($this->uri);
            }
        );
    }

    /**
     * Laravel 11 Modern Attribute: Check if menu item is active
     *
     * @return Attribute
     */
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn() => request()->is(trim($this->uri, '/') . '*')
        );
    }

    /**
     * Laravel 11 Modern Attribute: Check if menu has children
     *
     * @return Attribute
     */
    protected function hasChildren(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->children()->exists()
        );
    }

    /**
     * Laravel 11 Modern Attribute: Menu depth level
     *
     * @return Attribute
     */
    protected function depth(): Attribute
    {
        return Attribute::make(
            get: function () {
                $depth = 0;
                $parent = $this->parent;
                
                while ($parent) {
                    $depth++;
                    $parent = $parent->parent;
                }
                
                return $depth;
            }
        );
    }

    /**
     * determine if enable menu bind permission.
     *
     * @return bool
     */
    public function withPermission()
    {
        return (bool) config('admin.menu_bind_permission');
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        static::treeBoot();

        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }
}
