<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\EmbeddedForm;
use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Validator;

class Embeds extends Field
{
    /**
     * @var \Closure
     */
    protected $builder = null;
    protected $view = 'admin::form.embeds';
    /**
     * Create a new HasMany field instance.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->column = $column;

        if (count($arguments) == 1) {
            $this->label = $this->formatLabel();
            $this->builder = $arguments[0];
        }

        if (count($arguments) == 2) {
            list($this->label, $this->builder) = $arguments;
        }
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
        $form = $this->buildEmbeddedForm();

        return $form->setOriginal($this->original)->prepare($input);
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationRules(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $input = array_only($input, $this->column);
        $rules = $attributes = $messages = $newInputs = [];
        $rel = $this->column;
        $availInput = $input;
        $array_key_attach_str = function (array $a, string $b, string $c = '.') {
            return call_user_func_array(
                'array_merge',
                array_map(function ($u, $v) use ($b, $c) {
                    return ["{$b}{$c}{$u}" => $v];
                }, array_keys($a), array_values($a))
            );
        };

        $array_clean_merge = function (array $a, $b) {
            return array_merge($a, call_user_func_array('array_merge', $b));
        };

        /** @var Field $field */
        foreach ($this->buildEmbeddedForm()->fields() as $field) {
            if (!$fieldRules = $field->getRules()) {
                continue;
            }

            $column = $field->column();
            $columns = is_array($column) ? $column : [$column];
            if ($field instanceof Field\MultipleSelect || $field instanceof Field\Listbox) {
                $availInput[$column] = array_filter($availInput[$column], 'strlen');
                $availInput[$column] = $availInput[$column] ? : null;
            }
            /*
             *
             * For single column field format rules to:
             * [
             *     'extra.name' => 'required'
             *     'extra.email' => 'required'
             * ]
             *
             * For multiple column field with rules like 'required':
             * 'extra' => [
             *     'start' => 'start_at'
             *     'end'   => 'end_at',
             * ]
             *
             * format rules to:
             * [
             *     'extra.start_atstart' => 'required'
             *     'extra.end_atend' => 'required'
             * ]
             */
            $newColumn = array_map(function ($k, $v) use ($rel) {
                //Fix ResetInput Function! A Headache Implementation!
                return !$k ? "{$rel}.{$v}" : "{$rel}.{$v}:{$k}";
            }, array_keys($columns), array_values($columns));

            $fieldRules = is_array($fieldRules) ? implode('|', $fieldRules) : $fieldRules;
            $newRules = array_map(function ($v) use ($fieldRules, $availInput) {
                list($k, $c) = explode('.', $v);
                //Fix ResetInput Function! A Headache Implementation!
                $col = explode(':', $c)[0];

                if (array_key_exists($col, $availInput[$k]) && is_array($availInput[$k][$col])) {
                    return $array_key_attach_str(preg_replace('/./', $fieldRules, $availInput[$k][$col]), $v, ':');
                }

                //May Have Problem in Dealing with File Upload in Edit Mode
                return [$v => $fieldRules];
            }, $newColumn);
            $rules = $array_clean_merge($rules, $newRules);
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationAttributes(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $input = array_only($input, $this->column);
        $rules = $attributes = $messages = $newInputs = [];
        $rel = $this->column;
        $availInput = $input;
        $array_key_attach_str = function (array $a, string $b, string $c = '.') {
            return call_user_func_array(
                'array_merge',
                array_map(function ($u, $v) use ($b, $c) {
                    return ["{$b}{$c}{$u}" => $v];
                }, array_keys($a), array_values($a))
            );
        };

        $array_clean_merge = function (array $a, $b) {
            return array_merge($a, call_user_func_array('array_merge', $b));
        };

        /** @var Field $field */
        foreach ($this->buildEmbeddedForm()->fields() as $field) {
            if (!$fieldRules = $field->getRules()) {
                continue;
            }

            $column = $field->column();
            $columns = is_array($column) ? $column : [$column];
            if ($field instanceof Field\MultipleSelect || $field instanceof Field\Listbox) {
                $availInput[$column] = array_filter($availInput[$column], 'strlen');
                $availInput[$column] = $availInput[$column] ? : null;
            }
            /*
             *
             * For single column field format rules to:
             * [
             *     'extra.name' => 'required'
             *     'extra.email' => 'required'
             * ]
             *
             * For multiple column field with rules like 'required':
             * 'extra' => [
             *     'start' => 'start_at'
             *     'end'   => 'end_at',
             * ]
             *
             * format rules to:
             * [
             *     'extra.start_atstart' => 'required'
             *     'extra.end_atend' => 'required'
             * ]
             */
            $newColumn = array_map(function ($k, $v) use ($rel) {
                //Fix ResetInput Function! A Headache Implementation!
                return !$k ? "{$rel}.{$v}" : "{$rel}.{$v}:{$k}";
            }, array_keys($columns), array_values($columns));

            $newAttributes = array_map(function ($v) use ($field, $availInput) {
                list($k, $c) = explode('.', $v);
                //Fix ResetInput Function! A Headache Implementation!
                $col = explode(':', $c)[0];
                if (array_key_exists($col, $availInput[$k]) && is_array($availInput[$k][$col])) {
                    return call_user_func_array('array_merge', array_map(function ($u) use ($v, $field) {
                        $w = $field->label();
                        //Fix ResetInput Function! A Headache Implementation!
                        $w .= is_array($field->column()) ? '[' . explode(':', explode('.', $v)[2])[0] . ']' : '';

                        return ["{$v}:{$u}" => $w];
                    }, array_keys($availInput[$k][$col])));
                }

                //May Have Problem in Dealing with File Upload in Edit Mode
                $w = $field->label();
                //Fix ResetInput Function! A Headache Implementation!
                $w .= is_array($field->column()) ? '[' . explode(':', explode('.', $v)[2])[0] . ']' : '';

                return [$v => $w];
            }, $newColumn);
            $attributes = $array_clean_merge($attributes, $newAttributes);

        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationInput(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $input = array_only($input, $this->column);
        $rules = $attributes = $messages = $newInputs = [];
        $rel = $this->column;
        $availInput = $input;
        $array_key_attach_str = function (array $a, string $b, string $c = '.') {
            return call_user_func_array(
                'array_merge',
                array_map(function ($u, $v) use ($b, $c) {
                    return ["{$b}{$c}{$u}" => $v];
                }, array_keys($a), array_values($a))
            );
        };

        $array_clean_merge = function (array $a, $b) {
            return array_merge($a, call_user_func_array('array_merge', $b));
        };

        /** @var Field $field */
        foreach ($this->buildEmbeddedForm()->fields() as $field) {
            if (!$fieldRules = $field->getRules()) {
                continue;
            }

            $column = $field->column();
            $columns = is_array($column) ? $column : [$column];
            if ($field instanceof Field\MultipleSelect || $field instanceof Field\Listbox) {
                $availInput[$column] = array_filter($availInput[$column], 'strlen');
                $availInput[$column] = $availInput[$column] ? : null;
            }
            /*
             *
             * For single column field format rules to:
             * [
             *     'extra.name' => 'required'
             *     'extra.email' => 'required'
             * ]
             *
             * For multiple column field with rules like 'required':
             * 'extra' => [
             *     'start' => 'start_at'
             *     'end'   => 'end_at',
             * ]
             *
             * format rules to:
             * [
             *     'extra.start_atstart' => 'required'
             *     'extra.end_atend' => 'required'
             * ]
             */
            $newColumn = array_map(function ($k, $v) use ($rel) {
                //Fix ResetInput Function! A Headache Implementation!
                return !$k ? "{$rel}.{$v}" : "{$rel}.{$v}:{$k}";
            }, array_keys($columns), array_values($columns));

            $newInput = array_map(function ($v) use ($availInput, $array_key_attach_str) {
                list($k, $c) = explode('.', $v);
                //Fix ResetInput Function! A Headache Implementation!
                $col = explode(':', $c)[0];
                if (!array_key_exists($col, $availInput[$k])) {
                    //May Have Problem in Dealing with File Upload in Edit Mode
                    return [$v => null];
                }

                if (array_key_exists($col, $availInput[$k]) && is_array($availInput[$k][$col])) {
                    return $array_key_attach_str($availInput[$k][$col], $v, ':');
                }

                return [$v => $availInput[$k][$col]];
            }, $newColumn);
            $newInputs = $array_clean_merge($newInputs, $newInput);

        }

        return $newInputs;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationMessages(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $input = array_only($input, $this->column);
        $rules = $attributes = $messages = $newInputs = [];
        $rel = $this->column;
        $availInput = $input;
        $array_key_attach_str = function (array $a, string $b, string $c = '.') {
            return call_user_func_array(
                'array_merge',
                array_map(function ($u, $v) use ($b, $c) {
                    return ["{$b}{$c}{$u}" => $v];
                }, array_keys($a), array_values($a))
            );
        };

        $array_clean_merge = function (array $a, $b) {
            return array_merge($a, call_user_func_array('array_merge', $b));
        };

        /** @var Field $field */
        foreach ($this->buildEmbeddedForm()->fields() as $field) {
            if (!$fieldRules = $field->getRules()) {
                continue;
            }

            $column = $field->column();
            $columns = is_array($column) ? $column : [$column];
            if ($field instanceof Field\MultipleSelect || $field instanceof Field\Listbox) {
                $availInput[$column] = array_filter($availInput[$column], 'strlen');
                $availInput[$column] = $availInput[$column] ? : null;
            }
            /*
             *
             * For single column field format rules to:
             * [
             *     'extra.name' => 'required'
             *     'extra.email' => 'required'
             * ]
             *
             * For multiple column field with rules like 'required':
             * 'extra' => [
             *     'start' => 'start_at'
             *     'end'   => 'end_at',
             * ]
             *
             * format rules to:
             * [
             *     'extra.start_atstart' => 'required'
             *     'extra.end_atend' => 'required'
             * ]
             */
            $newColumn = array_map(function ($k, $v) use ($rel) {
                //Fix ResetInput Function! A Headache Implementation!
                return !$k ? "{$rel}.{$v}" : "{$rel}.{$v}:{$k}";
            }, array_keys($columns), array_values($columns));

            if ($field->validationMessages) {
                $newMessages = array_map(function ($v) use ($field, $availInput, $array_key_attach_str) {
                    list($k, $c) = explode('.', $v);
                    //Fix ResetInput Function! A Headache Implementation!
                    $col = explode(':', $c)[0];
                    if (array_key_exists($col, $availInput[$k]) && is_array($availInput[$k][$col])) {
                        return call_user_func_array('array_merge', array_map(function ($u) use ($v, $field, $array_key_attach_str) {
                            return $array_key_attach_str($field->validationMessages, "{$v}:{$u}");
                        }, array_keys($availInput[$k][$col])));
                    }

                    //May Have Problem in Dealing with File Upload in Edit Mode
                    return $array_key_attach_str($field->validationMessages, $v);
                }, $newColumn);
                $messages = $array_clean_merge($messages, $newMessages);
            }
        }

        return $messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidator(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $rules = $this->getValidationRules($input);
        if (empty($rules)) {
            return false;
        }

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

        $array_key_clean = function (array $a) {
            $a = count($a) ? call_user_func_array('array_merge', array_map(function ($k, $v) {
                return [str_replace(':', '', $k) => $v];
            }, array_keys($a), array_values($a))) : $a;

            return $a;
        };

        $attributes = $this->getValidationAttributes($input);
        $messages = $this->getValidationMessages($input);
        $newInputs = $this->getValidationInput($input);

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
     * Get data for Embedded form.
     *
     * Normally, data is obtained from the database.
     *
     * When the data validation errors, data is obtained from session flash.
     *
     * @return array
     */
    protected function getEmbeddedData()
    {
        if ($old = old($this->column)) {
            return $old;
        }

        if (empty($this->value)) {
            return [];
        }

        if (is_string($this->value)) {
            return json_decode($this->value, true);
        }

        return (array)$this->value;
    }

    /**
     * Build a Embedded Form and fill data.
     *
     * @return EmbeddedForm
     */
    protected function buildEmbeddedForm()
    {
        $form = new EmbeddedForm($this->elementName?:$this->column);

        $form->setParent($this->form);

        call_user_func($this->builder, $form);

        if($this->elementName)
        {
            list($rel,$key,$col)=explode('.',$this->errorKey);
            $form->fields()->each(function (Field $field) use ($rel,$key,$col){
                $column = $field->column();
                $elementName = $elementClass = $errorKey = [];
                if (is_array($column)) {
                    foreach ($column as $k => $name) {
                        $errorKey[$k] = sprintf('%s.%s.%s.%s',$rel,$key,$col,$name);
                        $elementName[$k] = sprintf('%s[%s][%s][%s]',$rel,$key,$col,$name);
                        $elementClass[$k] = [$rel,$col, $name];
                    }
                } else {
                    $errorKey = sprintf('%s.%s.%s.%s', $rel, $key, $col,$column);
                    $elementName = sprintf('%s[%s][%s][%s]', $rel, $key, $col,$column);
                    $elementClass = [$rel,$col, $column];
                }
                $field->setErrorKey($errorKey)
                    ->setElementName($elementName)
                    ->setElementClass($elementClass);
            });
        }
        $form->fill($this->getEmbeddedData());

        return $form;
    }


    /**
     * Render the form.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return parent::render()->with(['form' => $this->buildEmbeddedForm()]);
    }
}
