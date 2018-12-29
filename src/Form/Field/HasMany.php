<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\NestedForm;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class HasMany.
 */
class HasMany extends Field
{
    /**
     * Relation name.
     *
     * @var string
     */
    protected $relationName = '';

    /**
     * Form builder.
     *
     * @var \Closure
     */
    protected $builder = null;

    /**
     * Form data.
     *
     * @var array
     */
    protected $value = [];

    /**
     * View Mode.
     *
     * Supports `default` and `tab` currently.
     *
     * @var string
     */
    protected $viewMode = 'default';

    /**
     * Available views for HasMany field.
     *
     * @var array
     */
    protected $views = [
        'default' => 'admin::form.hasmany',
        'tab' => 'admin::form.hasmanytab',
    ];

    /**
     * Options for template.
     *
     * @var array
     */
    protected $options = [
        'allowCreate' => true,
        'allowDelete' => true,
    ];

    /**
     * Create a new HasMany field instance.
     *
     * @param $relationName
     * @param array $arguments
     */
    public function __construct($relationName, $arguments = [])
    {
        $this->relationName = $relationName;

        $this->column = $relationName;

        if (count($arguments) == 1) {
            $this->label = $this->formatLabel();
            $this->builder = $arguments[0];
        }

        if (count($arguments) == 2) {
            list($this->label, $this->builder) = $arguments;
        }
    }

    /**
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|Validator
     */
    public function getValidator(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $array_key_attach_str = function (array $a, string $b, string $c = '.') {
            return call_user_func_array(
                'array_merge',
                array_map(function ($u, $v) use ($b, $c) {
                    return ["{$b}{$c}{$u}" => $v];
                }, array_keys($a), array_values($a))
            );
        };

        $array_key_clean = function (array $a) {
            $a = count($a) ? call_user_func_array('array_merge', array_map(function ($k, $v) {
                return [str_replace(':', '', $k) => $v];
            }, array_keys($a), array_values($a))) : $a;

            return $a;
        };

        $array_clean_merge = function (array $a, $b) {
            return array_merge(
                $a,
                call_user_func_array(
                    'array_merge',
                    array_filter(
                        $b,
                        'strlen',
                        ARRAY_FILTER_USE_KEY
                    )
                )
            );
        };

        $array_key_clean_undot = function (array $a) {
            $keys = preg_grep('/[\.\:]/', array_keys($a));
            if ($keys) {
                foreach ($keys as $key) {
                    array_set($a, str_replace(':', '', $key), $a[$key]);
                    unset($a[$key]);
                }
            }

            return $a;
        };

        $input = array_only($input, $this->column);
        $form = $this->buildNestedForm($this->column, $this->builder);
        $rel = $this->relationName;
        $rules = $attributes = $messages = $newInputs = [];
        // remove all inputs & keys marked as removed
        $availInput = array_filter(array_map(function ($v) {
            return $v[NestedForm::REMOVE_FLAG_NAME] ? null : $v;
        }, $input[$rel]));
        $keys = array_keys($availInput);
        /* @var Field $field */
        foreach ($form->fields() as $field) {
            if (!$fieldRules = $field->getRules()) {
                continue;
            }
            $column = $field->column();
            $columns = is_array($column) ? $column : [$column];
            if ($field instanceof Field\MultipleSelect) {
                foreach ($keys as $key) {
                    $availInput[$key][$column] = array_filter($availInput[$key][$column], 'strlen') ?: null;
                }
            }

            $newColumn = call_user_func_array('array_merge', array_map(function ($u) use ($columns, $rel) {
                return array_map(function ($k, $v) use ($u, $rel) {
                    //Fix ResetInput Function! A Headache Implementation!
                    return $k ? "{$rel}.{$u}.{$v}:{$k}" : "{$rel}.{$u}.{$v}";
                }, array_keys($columns), array_values($columns));
            }, $keys));

            $fieldRules = is_array($fieldRules) ? implode('|', $fieldRules) : $fieldRules;
            $newRules = array_map(function ($v) use ($fieldRules, $availInput, $array_key_attach_str) {
                list($r, $k, $c) = explode('.', $v);
                //Fix ResetInput Function! A Headache Implementation!
                $col = explode(':', $c)[0];
                if (!array_key_exists($col, $availInput[$k])) {
                    return [null => null];
                }

                if (is_array($availInput[$k][$col])) {
                    return $array_key_attach_str(preg_replace('/./', $fieldRules, $availInput[$k][$col]), $v, ':');
                }

                return [$v => $fieldRules];
            }, $newColumn);
            $rules = $array_clean_merge($rules, $newRules);

            $newInput = array_map(function ($v) use ($availInput, $array_key_attach_str) {
                list($r, $k, $c) = explode('.', $v);
                //Fix ResetInput Function! A Headache Implementation!
                $col = explode(':', $c)[0];
                if (!array_key_exists($col, $availInput[$k])) {
                    return [null => null];
                }

                if (is_array($availInput[$k][$col])) {
                    return $array_key_attach_str($availInput[$k][$col], $v, ':');
                }

                return [$v => $availInput[$k][$col]];
            }, $newColumn);
            $newInputs = $array_clean_merge($newInputs, $newInput);

            $newAttributes = array_map(function ($v) use ($field, $availInput) {
                list($r, $k, $c) = explode('.', $v);
                //Fix ResetInput Function! A Headache Implementation!
                $col = explode(':', $c)[0];
                if (!array_key_exists($col, $availInput[$k])) {
                    return [null => null];
                }

                if (is_array($availInput[$k][$col])) {
                    return call_user_func_array('array_merge', array_map(function ($u) use ($v, $field) {
                        $w = $field->label();
                        //Fix ResetInput Function! A Headache Implementation!
                        $w .= is_array($field->column()) ? '['.explode(':', explode('.', $v)[2])[0].']' : '';
                        return ["{$v}:{$u}" => $w];
                    }, array_keys($availInput[$k][$col])));
                }

                $w = $field->label();
                //Fix ResetInput Function! A Headache Implementation!
                $w .= is_array($field->column()) ? '['.explode(':', explode('.', $v)[2])[0].']' : '';

                return [$v => $w];
            }, $newColumn);
            $attributes = $array_clean_merge($attributes, $newAttributes);

            if ($field->validationMessages) {
                $newMessages = array_map(function ($v) use ($field, $availInput, $array_key_attach_str) {
                    list($r, $k, $c) = explode('.', $v);
                    //Fix ResetInput Function! A Headache Implementation!
                    $col = explode(':', $c)[0];
                    if (!array_key_exists($col, $availInput[$k])) {
                        return [null => null];
                    }
                    if (is_array($availInput[$k][$col])) {
                        return call_user_func_array('array_merge', array_map(function ($u)
                            use ($v, $field, $array_key_attach_str) {
                            return $array_key_attach_str($field->validationMessages, "{$v}:{$u}");
                        }, array_keys($availInput[$k][$col])));
                    }

                    return $array_key_attach_str($field->validationMessages, $v);
                }, $newColumn);
                $messages = $array_clean_merge($messages, $newMessages);
            }
        }

        $rules = array_filter($rules, 'strlen');
        if (empty($rules)) {
            return false;
        }

        $attributes = array_filter($attributes, 'strlen');
        $messages = array_filter($messages, 'strlen');
        $input = $array_key_clean_undot(array_filter($newInputs, 'strlen', ARRAY_FILTER_USE_KEY));
        $rules = $array_key_clean($rules);
        $attributes = $array_key_clean($attributes);
        $messages = $array_key_clean($messages);

        if (empty($input)) {
            $input = [$rel => $availInput];
        }

        return Validator::make($input, $rules, $messages, $attributes);
    }

    /**
     * Prepare input data for insert or update.
     *
     * @param array $input
     *
     * @return array
     */
    public function prepare($input)
    {
        $form = $this->buildNestedForm($this->column, $this->builder);

        return $form->setOriginal($this->original, $this->getKeyName())->prepare($input);
    }

    /**
     * Build a Nested form.
     *
     * @param string   $column
     * @param \Closure $builder
     * @param null     $key
     *
     * @return NestedForm
     */
    protected function buildNestedForm($column, \Closure $builder, $key = null)
    {
        $form = new Form\NestedForm($column, $key);

        $form->setForm($this->form);

        call_user_func($builder, $form);

        $form->hidden($this->getKeyName());

        $form->hidden(NestedForm::REMOVE_FLAG_NAME)->default(0)->addElementClass(NestedForm::REMOVE_FLAG_CLASS);

        return $form;
    }

    /**
     * Get the HasMany relation key name.
     *
     * @return string
     */
    protected function getKeyName()
    {
        if (is_null($this->form)) {
            return;
        }

        return $this->form->model()->{$this->relationName}()->getRelated()->getKeyName();
    }

    /**
     * Set view mode.
     *
     * @param string $mode currently support `tab` mode.
     *
     * @return $this
     *
     * @author Edwin Hui
     */
    public function mode($mode)
    {
        $this->viewMode = $mode;

        return $this;
    }

    /**
     * Use tab mode to showing hasmany field.
     *
     * @return HasMany
     */
    public function useTab()
    {
        return $this->mode('tab');
    }

    /**
     * Build Nested form for related data.
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function buildRelatedForms()
    {
        if (is_null($this->form)) {
            return [];
        }

        $model = $this->form->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation && !$relation instanceof MorphMany) {
            throw new \Exception('hasMany field must be a HasMany or MorphMany relation.');
        }

        $forms = [];

        /*
         * If redirect from `exception` or `validation error` page.
         *
         * Then get form data from session flash.
         *
         * Else get data from database.
         */
        if ($values = old($this->column)) {
            foreach ($values as $key => $data) {
                if ($data[NestedForm::REMOVE_FLAG_NAME] == 1) {
                    continue;
                }

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder, $key)
                    ->fill($data);
            }
        } else {
            foreach ($this->value as $data) {
                $key = array_get($data, $relation->getRelated()->getKeyName());

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder, $key)
                    ->fill($data);
            }
        }

        return $forms;
    }

    /**
     * Setup script for this field in different view mode.
     *
     * @param string $script
     *
     * @return void
     */
    protected function setupScript($script)
    {
        $method = 'setupScriptFor' . ucfirst($this->viewMode) . 'View';

        call_user_func([$this, $method], $script);
    }

    /**
     * Setup default template script.
     *
     * @param string $templateScript
     *
     * @return void
     */
    protected function setupScriptForDefaultView($templateScript)
    {
        $removeClass = NestedForm::REMOVE_FLAG_CLASS;
        $defaultKey = NestedForm::DEFAULT_KEY_NAME;

        /**
         * When add a new sub form, replace all element key in new sub form.
         *
         * @example comments[new___key__][title]  => comments[new_{index}][title]
         *
         * {count} is increment number of current sub form count.
         */
        $script = <<<EOT
var index = 0;
$('#has-many-{$this->column}').on('click', '.add', function() {

    var tpl = $('template.{$this->column}-tpl');

    index++;

    var template = tpl.html().replace(/{$defaultKey}/g, index);
    $('.has-many-{$this->column}-forms').append(template);
    {$templateScript}
});

$('#has-many-{$this->column}').on('click', '.remove', function() {
    $(this).closest('.has-many-{$this->column}-form').hide();
    $(this).closest('.has-many-{$this->column}-form').find('.$removeClass').val(1);
});

EOT;

        Admin::script($script);
    }

    /**
     * Setup tab template script.
     *
     * @param string $templateScript
     *
     * @return void
     */
    protected function setupScriptForTabView($templateScript)
    {
        $removeClass = NestedForm::REMOVE_FLAG_CLASS;
        $defaultKey = NestedForm::DEFAULT_KEY_NAME;

        $script = <<<EOT

$('#has-many-{$this->column} > .nav').off('click', 'i.close-tab').on('click', 'i.close-tab', function(){
    var \$navTab = $(this).siblings('a');
    var \$pane = $(\$navTab.attr('href'));
    if( \$pane.hasClass('new') ){
        \$pane.remove();
    }else{
        \$pane.removeClass('active').find('.$removeClass').val(1);
    }
    if(\$navTab.closest('li').hasClass('active')){
        \$navTab.closest('li').remove();
        $('#has-many-{$this->column} > .nav > li:nth-child(1) > a').tab('show');
    }else{
        \$navTab.closest('li').remove();
    }
});

var index = 0;
$('#has-many-{$this->column} > .header').off('click', '.add').on('click', '.add', function(){
    index++;
    var navTabHtml = $('#has-many-{$this->column} > template.nav-tab-tpl').html().replace(/{$defaultKey}/g, index);
    var paneHtml = $('#has-many-{$this->column} > template.pane-tpl').html().replace(/{$defaultKey}/g, index);
    $('#has-many-{$this->column} > .nav').append(navTabHtml);
    $('#has-many-{$this->column} > .tab-content').append(paneHtml);
    $('#has-many-{$this->column} > .nav > li:last-child a').tab('show');
    {$templateScript}
});

if ($('.has-error').length) {
    $('.has-error').parent('.tab-pane').each(function() {
        var tabId = '#'+$(this).attr('id');
        $('li a[href="'+tabId+'"] i').removeClass('hide');
    });
    
    var first = $('.has-error:first').parent().attr('id');
    $('li a[href="#'+first+'"]').tab('show');
}
EOT;

        Admin::script($script);
    }

    /**
     * Disable create button.
     *
     * @return $this
     */
    public function disableCreate()
    {
        $this->options['allowCreate'] = false;

        return $this;
    }

    /**
     * Disable delete button.
     *
     * @return $this
     */
    public function disableDelete()
    {
        $this->options['allowDelete'] = false;

        return $this;
    }

    /**
     * Render the `HasMany` field.
     *
     * @throws \Exception
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // specify a view to render.
        $this->view = $this->views[$this->viewMode];

        list($template, $script) = $this->buildNestedForm($this->column, $this->builder)
            ->getTemplateHtmlAndScript();

        $this->setupScript($script);

        return parent::render()->with([
            'forms' => $this->buildRelatedForms(),
            'template' => $template,
            'relationName' => $this->relationName,
            'options' => $this->options,
        ]);
    }
}
