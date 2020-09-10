<?php

namespace Encore\Admin\Actions;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * @method $this                success($title, $text = '', $options = [])
 * @method $this                error($title, $text = '', $options = [])
 * @method $this                warning($title, $text = '', $options = [])
 * @method $this                info($title, $text = '', $options = [])
 * @method $this                question($title, $text = '', $options = [])
 * @method $this                confirm($title, $text = '', $options = [])
 * @method Field\Text           text($column, $label = '')
 * @method Field\Email          email($column, $label = '')
 * @method Field\Integer        integer($column, $label = '')
 * @method Field\Ip             ip($column, $label = '')
 * @method Field\Url            url($column, $label = '')
 * @method Field\Password       password($column, $label = '')
 * @method Field\Mobile         mobile($column, $label = '')
 * @method Field\Textarea       textarea($column, $label = '')
 * @method Field\Select         select($column, $label = '')
 * @method Field\MultipleSelect multipleSelect($column, $label = '')
 * @method Field\Checkbox       checkbox($column, $label = '')
 * @method Field\Radio          radio($column, $label = '')
 * @method Field\File           file($column, $label = '')
 * @method Field\Image          image($column, $label = '')
 * @method Field\MultipleFile   multipleFile($column, $label = '')
 * @method Field\MultipleImage  multipleImage($column, $label = '')
 * @method Field\Date           date($column, $label = '')
 * @method Field\Datetime       datetime($column, $label = '')
 * @method Field\Time           time($column, $label = '')
 * @method Field\Hidden         hidden($column, $label = '')
 * @method $this                modalLarge()
 * @method $this                modalSmall()
 */
abstract class Action implements Renderable
{
    use Authorizable;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $selector;

    /**
     * @var string
     */
    public $event = 'click';

    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var string
     */
    public $selectorPrefix = '.action-';

    /**
     * @var Interactor\Interactor
     */
    protected $interactor;

    /**
     * @var array
     */
    protected static $selectors = [];

    /**
     * @var string
     */
    public $name;

    /**
     * Action constructor.
     */
    public function __construct()
    {
        $this->initInteractor();
    }

    /**
     * @throws \Exception
     */
    protected function initInteractor()
    {
        if ($hasForm = method_exists($this, 'form')) {
            $this->interactor = new Interactor\Form($this);
        }

        if ($hasDialog = method_exists($this, 'dialog')) {
            $this->interactor = new Interactor\Dialog($this);
        }

        if ($hasForm && $hasDialog) {
            throw new \Exception('Can only define one of the methods in `form` and `dialog`');
        }
    }

    /**
     * Get batch action title.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $prefix
     *
     * @return mixed|string
     */
    public function selector($prefix)
    {
        if (is_null($this->selector)) {
            return static::makeSelector(get_called_class(), $prefix);
        }

        return $this->selector;
    }

    /**
     * @param string $class
     * @param string $prefix
     *
     * @return string
     */
    public static function makeSelector($class, $prefix)
    {
        if (!isset(static::$selectors[$class])) {
            static::$selectors[$class] = uniqid($prefix).mt_rand(1000, 9999);
        }

        return static::$selectors[$class];
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function attribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Format the field attributes.
     *
     * @return string
     */
    protected function formatAttributes()
    {
        return admin_attrs($this->attributes);
    }

    /**
     * @return string
     */
    public function getElementClass()
    {
        return ltrim($this->selector($this->selectorPrefix), '.');
    }

    /**
     * @return Response
     */
    public function response()
    {
        if (is_null($this->response)) {
            $this->response = new Response();
        }

        if (method_exists($this, 'dialog')) {
            $this->response->swal();
        } else {
            $this->response->toastr();
        }

        return $this->response;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getCalledClass()
    {
        return str_replace('\\', '_', get_called_class());
    }

    /**
     * @return string
     */
    public function getHandleUrl()
    {
        return admin_url('_handle_action_');
    }

    /**
     * @return string
     */
    protected function getModelClass()
    {
        return '';
    }

    /**
     * @return array
     */
    public function parameters()
    {
        return [];
    }

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function validate(Request $request)
    {
        if ($this->interactor instanceof Interactor\Form) {
            $this->interactor->validate($request);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    protected function addScript()
    {
        $data = [
            'title'         => $this->name(),
            'event'         => $this->event,
            'selector'      => $this->selector($this->selectorPrefix),
            'parameters'    => array_merge($this->parameters(), ['_action' => $this->getCalledClass()]),
            'action_script' => $this->actionScript(),
            'method'        => $this->getMethod(),
            'url'           => $this->getHandleUrl(),
        ];

        if ($this->interactor) {
            return $this->interactor->addScript($data);
        }

        return Admin::view('admin::actions.action', $data);
    }

    /**
     * @return string
     */
    public function actionScript()
    {
        return '';
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        if (in_array($method, Interactor\Interactor::$elements)) {
            return $this->interactor->{$method}(...$arguments);
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    /**
     * @return string
     */
    public function html()
    {
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $this->addScript();

        $content = $this->html();

        if ($content && $this->interactor instanceof Interactor\Form) {
            return $this->interactor->addElementAttr($content, $this->selector);
        }

        return $content;
    }
}
