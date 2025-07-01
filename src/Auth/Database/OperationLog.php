<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class OperationLog extends Model
{
    use DefaultDatetimeFormat;
    use HasFactory;

    protected $fillable = ['user_id', 'path', 'method', 'ip', 'input'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'user_id' => 'integer',
        'input' => AsArrayObject::class, // Laravel 11: Cast JSON to ArrayObject
    ];

    public static $methodColors = [
        'GET'    => 'green',
        'POST'   => 'yellow',
        'PUT'    => 'blue',
        'DELETE' => 'red',
    ];

    public static $methods = [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH',
        'LINK', 'UNLINK', 'COPY', 'HEAD', 'PURGE',
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

        $this->setTable(config('admin.database.operation_log_table'));

        parent::__construct($attributes);
    }

    /**
     * Laravel 11 Modern Attribute: Method color for display
     *
     * @return Attribute
     */
    protected function methodColor(): Attribute
    {
        return Attribute::make(
            get: fn() => static::$methodColors[$this->method] ?? 'gray'
        );
    }

    /**
     * Laravel 11 Modern Attribute: Formatted path for display
     *
     * @return Attribute
     */
    protected function displayPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                $adminPrefix = config('admin.route.prefix', 'admin');
                return str_replace($adminPrefix . '/', '', $this->path);
            }
        );
    }

    /**
     * Laravel 11 Modern Attribute: Human readable timestamp
     *
     * @return Attribute
     */
    protected function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->diffForHumans()
        );
    }

    /**
     * Laravel 11 Modern Attribute: Check if operation was successful
     *
     * @return Attribute
     */
    protected function wasSuccessful(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Check for error indicators in input data
                if (!$this->input) return true;
                
                $input = is_array($this->input) ? $this->input : $this->input->toArray();
                return !isset($input['error']) && !isset($input['exception']);
            }
        );
    }

    /**
     * Laravel 11 Modern Attribute: Sanitized input for display
     *
     * @return Attribute
     */
    protected function sanitizedInput(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->input) return [];
                
                $input = is_array($this->input) ? $this->input : $this->input->toArray();
                
                // Remove sensitive fields
                $sensitive = ['password', 'password_confirmation', 'token', 'secret'];
                
                foreach ($sensitive as $field) {
                    if (isset($input[$field])) {
                        $input[$field] = '***HIDDEN***';
                    }
                }
                
                return $input;
            }
        );
    }

    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('admin.database.users_model'));
    }
}
