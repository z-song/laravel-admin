<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\EmbeddedForm;
use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    public function getValidator(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $input = array_only($input, $this->column);

        $rules = $attributes = $messages = [];
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

        $array_key_clean = function (array $a) {
            $a = count($a) ? call_user_func_array('array_merge', array_map(function ($k, $v) {
                return [str_replace(':', '', $k) => $v];
            }, array_keys($a), array_values($a))) : $a;

            return $a;
        };

        $array_key_clean_undot = function (array $a) {
            if (count($a)) {
                foreach ($a as $key => $val) {
                    array_set($a, str_replace(':', '', $key), $val);
                    if (preg_match('/[\.\:]/', $key)) {
                        unset($a[$key]);
                    }
                }
            }
            return $a;
        };

        /** @var Field $field */
        foreach ($this->buildEmbeddedForm()->fields() as $field) {
            if (!$fieldRules = $field->getRules()) {
                continue;
            }

            $column = $field->column();
            $columns = is_array($column) ? $column : [$column];
            if ($field instanceof Field\MultipleSelect) {
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
            $rules = array_merge($rules, call_user_func_array(
                'array_merge',
                array_map(function ($v) use ($fieldRules) {
                    return [$v => $fieldRules];
                }, $newColumn)
            ));
            $attributes = array_merge($attributes, call_user_func_array(
                'array_merge',
                array_map(function ($v) use ($field) {
                    //Fix ResetInput Function! A Headache Implementation!
                    $u = $field->label();
                    $u .= is_array($field->column()) ? '[' . explode(':', explode('.', $v)[1])[0] . ']' : '';

                    return [$v => "{$u}"];
                }, $newColumn)
            ));
            if ($field->validationMessages) {
                $newMessages = array_map(function ($v) use ($field, $availInput, $array_key_attach_str) {
                    list($rel, $col) = explode('.', $v);
                    //Fix ResetInput Function! A Headache Implementation!
                    $col1 = explode(':', $col)[0];
                    if (!array_key_exists($col1, $availInput[$rel])) {
                        return [null => null];
                    }
                    $rows = $availInput[$rel][$col1];
                    if (!is_array($rows)) {
                        return $array_key_attach_str($field->validationMessages, $v);
                    }
                    $r = [];
                    foreach (array_keys($rows) as $k) {
                        $k = "{$v}{$k}";
                        $r = array_merge($r, $array_key_attach_str($field->validationMessages, $k));
                    }

                    return $r;
                }, $newColumn);
                $newMessages = call_user_func_array('array_merge', $newMessages);
                $messages = array_merge($messages, $newMessages);
            }
        }

        if (empty($rules)) {
            return false;
        }
        $newInput = call_user_func_array('array_merge', array_map(function ($idx) use ($availInput) {
            list($rel, $col) = explode('.', $idx);
            //Fix ResetInput Function! A Headache Implementation!
            $col1 = explode(':', $col)[0];
            if (!array_key_exists($col1, $availInput[$rel])) {
                return [null => null];
            }
            if (is_array($availInput[$rel][$col1])) {
                return call_user_func_array('array_merge', array_map(function ($x, $y) use ($idx) {
                    return ["{$idx}{$x}" => $y];
                }, array_keys($availInput[$rel][$col1]), $availInput[$rel][$col1]));
            }

            return ["{$idx}" => $availInput[$rel][$col1]];
        }, array_keys($rules)));

        $newInput = $array_key_clean_undot($newInput);
        $newRules = array_map(function ($idx) use ($availInput, $rules) {
            list($rel, $col) = explode('.', $idx);
            //Fix ResetInput Function! A Headache Implementation!
            $col1 = explode(':', $col)[0];
            if (!array_key_exists($col1, $availInput[$rel])) {
                return [null => null];
            }
            if (is_array($availInput[$rel][$col1])) {
                return call_user_func_array('array_merge', array_map(function ($x) use ($idx, $rules) {
                    return ["{$idx}{$x}" => $rules[$idx]];
                }, array_keys($availInput[$rel][$col1])));
            }
            return ["{$idx}" => $rules[$idx]];
        }, array_keys($rules));
        $newRules = array_filter(call_user_func_array('array_merge', $newRules), 'strlen', ARRAY_FILTER_USE_KEY);
        $newRules = $array_key_clean($newRules);

        $newAttributes = array_map(function ($idx) use ($availInput, $attributes) {
            list($rel, $col) = explode('.', $idx);
            //Fix ResetInput Function! A Headache Implementation!
            $col1 = explode(':', $col)[0];
            if (!array_key_exists($col1, $availInput[$rel])) {
                return [null => null];
            }
            if (is_array($availInput[$rel][$col1])) {
                if (array_keys($availInput[$rel][$col1])) {
                    return call_user_func_array('array_merge', array_map(function ($x) use ($idx, $attributes) {
                        return ["{$idx}.{$x}" => $attributes[$idx]];
                    }, array_keys($availInput[$rel][$col1])));
                }
                return [null => null];
            }

            return ["{$idx}" => $attributes[$idx]];
        }, array_keys($attributes));
        $newAttributes = array_filter(call_user_func_array('array_merge', $newAttributes), 'strlen');
        $newAttributes = $array_key_clean($newAttributes);

        $messages = $array_key_clean($messages);

        if (empty($newInput)) {
            $newInput = $availInput;
        }
        return Validator::make($newInput, $newRules, $messages, $newAttributes);
    }



    /**
     * Format validation attributes.
     *
     * @param array  $input
     * @param string $label
     * @param string $column
     *
     * @return array
     */
    protected function formatValidationAttribute($input, $label, $column)
    {
        $new = $attributes = [];

        if (is_array($column)) {
            foreach ($column as $index => $col) {
                $new[$col . $index] = $col;
            }
        }

        foreach (array_keys(array_dot($input)) as $key) {
            if (is_string($column)) {
                if (Str::endsWith($key, ".$column")) {
                    $attributes[$key] = $label;
                }
            } else {
                foreach ($new as $k => $val) {
                    if (Str::endsWith($key, ".$k")) {
                        $attributes[$key] = $label . "[$val]";
                    }
                }
            }
        }

        return $attributes;
    }

    /**
     * Reset input key for validation.
     *
     * @param array $input
     * @param array $column $column is the column name array set
     *
     * @return void.
     */
    public function resetInputKey(array &$input, array $column)
    {
        $column = array_flip($column);

        foreach ($input[$this->column] as $key => $value) {
            if (!array_key_exists($key, $column)) {
                continue;
            }

            $newKey = $key . $column[$key];

            /*
             * set new key
             */
            array_set($input, "{$this->column}.$newKey", $value);
            /*
             * forget the old key and value
             */
            array_forget($input, "{$this->column}.$key");
        }
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
        $form = new EmbeddedForm($this->column);

        $form->setParent($this->form);

        call_user_func($this->builder, $form);

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
