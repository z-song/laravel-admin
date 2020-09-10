<?php

namespace Encore\Admin\Form\Concerns;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

trait HasHooks
{
    /**
     * Supported hooks: submitted, editing, saving, saved.
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

        try {
            foreach ($hooks as $func) {
                if (!$func instanceof Closure) {
                    continue;
                }

                $response = call_user_func($func, $this, $parameters);

                if ($response instanceof RedirectResponse) {
                    return \response([
                        'status'   => true,
                        'redirect' => $response->getTargetUrl(),
                    ]);
                }
            }
        } catch (\Exception $exception) {
            return \response([
                'status'    => false,
                'message'   => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Set after getting creating model callback.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function creating(Closure $callback)
    {
        return $this->registerHook('creating', $callback);
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
     * Call creating callbacks.
     *
     * @return mixed
     */
    protected function callCreating()
    {
        return $this->callHooks('creating');
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
}
