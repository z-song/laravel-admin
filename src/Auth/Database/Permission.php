<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Permission extends Model
{
    use DefaultDatetimeFormat;
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'http_method', 'http_path'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'http_method' => 'array', // Laravel 11: Auto-cast comma-separated to array
    ];

    /**
     * @var array
     */
    public static $httpMethods = [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD',
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

        $this->setTable(config('admin.database.permissions_table'));

        parent::__construct($attributes);
    }

    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        $pivotTable = config('admin.database.role_permissions_table');

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'permission_id', 'role_id');
    }

    /**
     * If request should pass through the current permission.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function shouldPassThrough(Request $request): bool
    {
        if (empty($this->http_method) && empty($this->http_path)) {
            return true;
        }

        $method = $this->http_method;

        $matches = array_map(function ($path) use ($method) {
            $path = trim(config('admin.route.prefix'), '/').$path;

            if (Str::contains($path, ':')) {
                list($method, $path) = explode(':', $path);
                $method = explode(',', $method);
            }

            return compact('method', 'path');
        }, explode("\n", $this->http_path));

        foreach ($matches as $match) {
            if ($this->matchRequest($match, $request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Laravel 11 Modern Attribute: Clean HTTP path
     *
     * @return Attribute
     */
    protected function httpPath(): Attribute
    {
        return Attribute::make(
            get: fn($value) => str_replace("\r\n", "\n", $value),
            set: fn($value) => str_replace("\r\n", "\n", $value)
        );
    }

    /**
     * If a request match the specific HTTP method and path.
     *
     * @param array   $match
     * @param Request $request
     *
     * @return bool
     */
    protected function matchRequest(array $match, Request $request): bool
    {
        if ($match['path'] == '/') {
            $path = '/';
        } else {
            $path = trim($match['path'], '/');
        }

        if (!$request->is($path)) {
            return false;
        }

        $method = collect($match['method'])->filter()->map(function ($method) {
            return strtoupper($method);
        });

        return $method->isEmpty() || $method->contains($request->method());
    }

    /**
     * Laravel 11 Modern Attribute: HTTP methods handling
     * Now using built-in array casting instead of manual conversion
     *
     * @return Attribute
     */
    protected function httpMethod(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_string($value)) {
                    return array_filter(explode(',', $value));
                }
                return $value ?: [];
            },
            set: function ($value) {
                if (is_array($value)) {
                    return implode(',', array_filter($value));
                }
                return $value;
            }
        );
    }

    /**
     * Laravel 11 Modern Attribute: Display name for permission
     *
     * @return Attribute
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn() => ucwords(str_replace(['-', '_'], ' ', $this->name))
        );
    }

    /**
     * Laravel 11 Modern Attribute: Check if this is a wildcard permission
     *
     * @return Attribute
     */
    protected function isWildcard(): Attribute
    {
        return Attribute::make(
            get: fn() => str_contains($this->http_path, '*') || empty($this->http_path)
        );
    }

    /**
     * Laravel 11 Modern Attribute: Count of roles with this permission
     *
     * @return Attribute
     */
    protected function rolesCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->roles()->count()
        );
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }
}
