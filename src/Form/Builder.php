<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Field;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

/**
 * Class Builder.
 */
class Builder
{
    /**
     *  Previous url key
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
     * @var Tab
     */
    protected $tab;

    /**
     * @var Group
     */
    protected $group;

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
     * @param Tab $tab
     */
    public function setTab(Tab $tab)
    {
        $this->tab = $tab;
    }

    /**
     * @return Tab
     */
    public function getTab()
    {
        return $this->tab;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
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
     * @param $fields
     */
    public function mergeFields($fields)
    {
        $this->fields = $this->fields->merge($fields);
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
            $attributes['action'] = $this->form->resource().'/'.$this->id;
            $this->form->hidden('_method')->value('PUT');
        }

        if ($this->mode == self::MODE_CREATE) {
            $attributes['action'] = $this->form->resource(-1);
        }

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

    public function ajaxSubmit()
    {
        if ($this->mode == self::MODE_VIEW) {
            return '';
        }

        $script = <<<SCRIPT
$('button.ajax-submit').on('click', function(){
    var \$form = $(this).closest('form');
    var url = \$form.attr('action');
    \$form.find('.has-error').removeClass('has-error');
    \$form.find('.error-label').remove();
    $.ajax({
      type: 'POST',
      url: url,
      data: \$form.serialize(),
      success: function(data){
        if(data.status === false){
            $.each(data.message, function(key, value){
                var \$target = $('[name="' + key + '"]:not([type="hidden"])');
                \$target.closest('.form-group').addClass('has-error');
                $.each(value, function(k, error){
                   \$target.before('<label class="control-label error-label" for="inputError"><i class="fa fa-times-circle-o"></i> ' + error + '</label>');
                });
            });
        }else if(data.status === true){
            $('section.content').prepend('<div class="alert alert-success alert-dismissable success-label">' +
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">��</button>' +
        '<h4><i class="icon fa fa-check"></i>Succeeded</h4>' +
        '<p>' + data.message + '</p>' +
        '</div>');
        $(window).scrollTop(0);
        };
        },
      error: function(data){
       $('html').html(data.responseText);
       $(window).scrollTop(0);
      }
    });

});
SCRIPT;

        Admin::script($script);

        return '<button type="button" class="btn btn-info pull-right ajax-submit">'.trans('admin::lang.submit').'</button>';
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

        $hidden = new Field\DataField\Hidden(static::PREVIOUS_URL_KEY);

        $this->fields->push($hidden->value($previous));
    }

    /**
     * Render
     *
     * @return string
     */
    protected function renderTabForm()
    {
        $tabs = $this->tab->getTabs()->map(function ($tab) {

            $form = new Form($this->form->model(), $tab['content']);

            // In edit mode.
            if ($this->isMode(static::MODE_EDIT)) {
                $form->edit($this->id);
            }

            return array_merge($tab, compact('form'));
        });

        $script = <<<SCRIPT

var url = document.location.toString();
if (url.match('#')) {
    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
}

// Change hash for page-reload
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
});

SCRIPT;

        Admin::script($script);

        return view('admin::form.tab', ['form' => $this, 'tabs' => $tabs])->render();
    }


    /**
     * get group with form data
     *
     * @return bool|static
     * author Edwin Hui
     */
    protected function getGroupWithFormData()
    {
        if( !empty($this->group)){

            return $this->group->getGroups()->map(function ($group) {

                $form = new Form($this->form->model(), $group['content']);

                // In edit mode.
                if ($this->isMode(static::MODE_EDIT)) {
                    $form->edit($this->id);
                }

                return array_merge($group, compact('form'));
            });
        }

        return false;

    }

    /**
     * Render
     *
     * @return string
     */
    protected function renderSimpleForm()
    {

        $groups = $this->getGroupWithFormData();

        $slice = $this->mode == static::MODE_CREATE ? -1 : -2;

        $script = <<<SCRIPT
$('.form-history-back').on('click', function () {
    event.preventDefault();
    history.back(1);
});
SCRIPT;

        Admin::script($script);

        $vars = [
            'id'       => $this->id,
            'form'     => $this,
            'resource' => $this->form->resource($slice),
            'groups' => $groups,
        ];

        $this->addRedirectUrlField();

        return view('admin::form', $vars)->render();
    }

    /**
     * Render form.
     *
     * @return string
     */
//    protected function renderSimpleForm()
//    {
//        $slice = $this->mode == static::MODE_CREATE ? -1 : -2;
//
//        $script = <<<SCRIPT
//$('.form-history-back').on('click', function () {
//    event.preventDefault();
//    history.back(1);
//});
//SCRIPT;
//
//        Admin::script($script);
//
//        $vars = [
//            'id'       => $this->id,
//            'form'     => $this,
//            'resource' => $this->form->resource($slice),
//        ];
//
//        $this->addRedirectUrlField();
//
//        return view('admin::form', $vars)->render();
//    }

    /**
     * @return string
     */
    public function render()
    {
//        if ($this->group){
//            return $this->renderGroupForm();
//        }
        if ($this->tab) {
            return $this->renderTabForm();
        }

        return $this->renderSimpleForm();
    }

    /**
     * @return mixed
     */
    public function renderWithoutForm()
    {
        return preg_replace(
            ['/<form[^>]+>/', '/<\/form>/'],
            ['<div class="form-horizontal">', '</div>'],
            $this->renderSimpleForm()
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
