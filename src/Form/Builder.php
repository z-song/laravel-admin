<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Class Builder.
 */
class Builder
{
    /**
     *  Previous url key.
     */
    const PREVIOUS_URL_KEY = '_previous_';

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var
     */
    protected $action;

    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Modes constants.
     */
    const MODE_VIEW = 'view';
    const MODE_EDIT = 'edit';
    const MODE_CREATE = 'create';

    /**
     * Form action mode, could be create|view|edit.
     *
     * @var string
     */
    protected $mode = 'create';

    /**
     * @var array
     */
    protected $hiddenFields = [];

    /**
     * Builder constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->fields = new Collection();
    }

    /**
     * Set the builder mode.
     *
     * @param string $mode
     *
     * @return void
     */
    public function setMode($mode = 'create')
    {
        $this->mode = $mode;
    }

    /**
     * Returns builder is $mode.
     *
     * @param $mode
     *
     * @return bool
     */
    public function isMode($mode)
    {
        return $this->mode == $mode;
    }

    /**
     * Set resource Id.
     *
     * @param $id
     *
     * @return void
     */
    public function setResourceId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        if ($this->mode == self::MODE_CREATE) {
            return $this->form->resource(-1);
        }

        return $this->form->resource();
    }

    /**
     * Set form action.
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get Form action.
     *
     * @return string
     */
    public function getAction()
    {
        if ($this->action) {
            return $this->action;
        }

        if ($this->isMode(static::MODE_EDIT)) {
            return $this->form->resource().'/'.$this->id;
        }

        if ($this->isMode(static::MODE_CREATE)) {
            return $this->form->resource(-1);
        }

        return '';
    }

    /**
     * Get fields of this builder.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getHiddenFields()
    {
        return $this->hiddenFields;
    }

    /**
     * @param Field $field
     *
     * @return void
     */
    public function addHiddenField(Field $field)
    {
        $this->hiddenFields[] = $field;
    }

    /**
     * Add or get options.
     *
     * @param array $options
     *
     * @return array|null
     */
    public function options($options = [])
    {
        if (empty($options)) {
            return $this->options;
        }

        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return string
     */
    public function title()
    {
        if ($this->mode == static::MODE_CREATE) {
            return trans('admin::lang.create');
        }

        if ($this->mode == static::MODE_EDIT) {
            return trans('admin::lang.edit');
        }

        if ($this->mode == static::MODE_VIEW) {
            return trans('admin::lang.view');
        }

        return '';
    }

    /**
     * Determine if form fields has files.
     *
     * @return bool
     */
    public function hasFile()
    {
        foreach ($this->fields() as $field) {
            if ($field instanceof Field\File) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add field for store redirect url after update or store.
     *
     * @return void
     */
    protected function addRedirectUrlField()
    {
        $previous = URL::previous();

        if (!$previous || $previous == URL::current()) {
            return;
        }

        if (Str::contains($previous, url($this->getResource()))) {
            $this->addHiddenField((new Form\Field\Hidden(static::PREVIOUS_URL_KEY))->value($previous));
        }
    }

    /**
     * Open up a new HTML form.
     *
     * @param array $options
     *
     * @return string
     */
    public function open($options = [])
    {
        $attributes = [];

        if ($this->mode == self::MODE_EDIT) {
            $this->addHiddenField((new Form\Field\Hidden('_method'))->value('PUT'));
        }

        $this->addRedirectUrlField();

        $attributes['action'] = $this->getAction();
        $attributes['method'] = array_get($options, 'method', 'post');
        $attributes['accept-charset'] = 'UTF-8';

        $attributes['class'] = array_get($options, 'class');

        if ($this->hasFile()) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        $html = [];
        foreach ($attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return '<form '.implode(' ', $html).' pjax-container>';
    }

    /**
     * Close the current form.
     *
     * @return string
     */
    public function close()
    {
        $this->form = null;
        $this->fields = null;

        return '</form>';
    }

    /**
     * Build submit button.
     *
     * @return string
     */
    public function submit()
    {
        if ($this->mode == self::MODE_VIEW) {
            return '';
        }

        return '<button type="submit" class="btn btn-info pull-right">'.trans('admin::lang.submit').'</button>';
    }

    /**
     * Remove reserved fields like `id` `created_at` `updated_at` in form fields.
     *
     * @return void
     */
    protected function removeReservedFields()
    {
        if (!$this->isMode(static::MODE_CREATE)) {
            return;
        }

        $reservedColumns = [
            $this->form->model()->getKeyName(),
            $this->form->model()->getCreatedAtColumn(),
            $this->form->model()->getUpdatedAtColumn(),
        ];

        $this->fields = $this->fields()->reject(function (Field $field) use ($reservedColumns) {
            return in_array($field->column(), $reservedColumns);
        });
    }

    /**
     * Render form.
     *
     * @return string
     */
    public function render()
    {
        $this->removeReservedFields();

        $tabObj = $this->form->getTab();

        $script = <<<'SCRIPT'
        
$('.form-history-back').on('click', function () {
    event.preventDefault();
    history.back(1);
});

SCRIPT;

        if (!$tabObj->isEmpty()) {
            $script .= <<<'SCRIPT'

var url = document.location.toString();
if (url.match('#')) {
    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
}
        
// Change hash for page-reload
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
});

if ($('.has-error').length) {
    $('.has-error').parent().each(function () {
        var tabId = '#'+$(this).attr('id');
        $('li a[href="'+tabId+'"] i').removeClass('hide');
    });
    
    var first = $('.has-error:first').parent().attr('id');
    $('li a[href="#'+first+'"]').tab('show');
}

SCRIPT;
        }

        Admin::script($script);

        $slice = $this->mode == static::MODE_CREATE ? -1 : -2;

        $data = [
            'form'     => $this,
            'resource' => $this->form->resource($slice),
            'tabObj'   => $tabObj,
        ];

        return view('admin::form', $data)->render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
