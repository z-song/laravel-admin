<?php

namespace Encore\Admin\Form\Concerns;

use Closure;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

trait HasHooks
{
    /**
     * Supported hooks: submitted, editing, saving, saved, deleting, deleted.
     *
     * @var array
     */
    protected $hooks = [];

    /**
     * Initialization closure array.
     *
     * @var []Closure
     */
    protected static $initCallbacks;

    /**
     * Initialize with user pre-defined default disables, etc.
     *
     * @param Closure $callback
     */
    public static function init(Closure $callback = null)
    {
        static::$initCallbacks[] = $callback;
    }

    /**
     * Call the initialization closure array in sequence.
     */
    protected function callInitCallbacks()
    {
        if (empty(static::$initCallbacks)) {
            return;
        }

        foreach (static::$initCallbacks as $callback) {
            $callback($this);
        }
    }

    /**
     * Register a hook.
     *
     * @param string  $name
     * @param Closure $callback
     *
     * @return $this
     */
    protected function registerHook($name, Closure $callback)
    {
        $this->hooks[$name][] = $callback;

        return $this;
    }

    /**
     * Call hooks by giving name.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return Response
     */
    protected function callHooks($name, $parameters = [])
    {
        $hooks = Arr::get($this->hooks, $name, []);

        foreach ($hooks as $func) {
            if (!$func instanceof Closure) {
                continue;
            }

            $response = call_user_func($func, $this, $parameters);

            if ($response instanceof Response) {
                return $response;
            }
        }
    }

    /**
     * Set after getting editing model callback.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function editing(Closure $callback)
    {
        return $this->registerHook('editing', $callback);
    }

    /**
     * Set submitted callback.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function submitted(Closure $callback)
    {
        return $this->registerHook('submitted', $callback);
    }

    /**
     * Set saving callback.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function saving(Closure $callback)
    {
        return $this->registerHook('saving', $callback);
    }

    /**
     * Set saved callback.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function saved(Closure $callback)
    {
        return $this->registerHook('saved', $callback);
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function deleting(Closure $callback)
    {
        return $this->registerHook('deleting', $callback);
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function deleted(Closure $callback)
    {
        return $this->registerHook('deleted', $callback);
    }

    /**
     * Call editing callbacks.
     *
     * @return mixed
     */
    protected function callEditing()
    {
        return $this->callHooks('editing');
    }

    /**
     * Call submitted callback.
     *
     * @return mixed
     */
    protected function callSubmitted()
    {
        return $this->callHooks('submitted');
    }

    /**
     * Call saving callback.
     *
     * @return mixed
     */
    protected function callSaving()
    {
        return $this->callHooks('saving');
    }

    /**
     * Callback after saving a Model.
     *
     * @return mixed|null
     */
    protected function callSaved()
    {
        return $this->callHooks('saved');
    }

    /**
     * Call hooks when deleting.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    protected function callDeleting($id)
    {
        return $this->callHooks('deleting', $id);
    }

    /**
     * @return mixed
     */
    protected function callDeleted()
    {
        return $this->callHooks('deleted');
    }
}
