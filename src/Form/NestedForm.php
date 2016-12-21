<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NestedForm
{
    const UPDATE_KEY_NAME_OLD = 'old';

    const UPDATE_KEY_NAME_NEW = 'new';

    const REMOVE_FLAG_NAME = '_remove';

    const REMOVE_FLAG_CLASS = 'fom-removed';

    /**
     * @var \Illuminate\Database\Eloquent\Relations\HasMany|string
     */
    protected $relation;

    /**
     * Fields in form.
     *
     * @var Collection
     */
    protected $fields;

    /**
     * Scripts of form.
     *
     * @var array
     */
    protected $scripts = [];

    /**
     * Original data for this field.
     *
     * @var array
     */
    protected $original = [];

    /**
     * Create a new NestedForm instance.
     *
     * @param $relation
     */
    public function __construct($relation)
    {
        $this->relation = $relation;

        $this->fields = new Collection();
    }

    /**
     * Set original values for fields.
     *
     * @param array $data
     * @param string $relatedKeyName
     *
     * @return $this
     */
    public function setOriginal($data, $relatedKeyName)
    {
        foreach ($data as $value) {
            $this->original[$value[$relatedKeyName]] = $value;
        }

        return $this;
    }

    /**
     * Prepare for insert or update.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function prepare($input)
    {
        if (array_key_exists(static::UPDATE_KEY_NAME_NEW, $input)) {
            $new = $input[static::UPDATE_KEY_NAME_NEW];

            $prepared = [];

            foreach ($this->formatInputArray($new) as $record) {
                $prepared[] = $this->prepareRecord($record);
            }

            $input[static::UPDATE_KEY_NAME_NEW] = $prepared;
        }

        if (array_key_exists(static::UPDATE_KEY_NAME_OLD, $input)) {
            $old = $input[static::UPDATE_KEY_NAME_OLD];

            $prepared = [];

            foreach ($old as $key => $record) {
                $this->setFieldOriginalValue($key);

                $prepared[$key] = $this->prepareRecord($record);
            }

            $input[static::UPDATE_KEY_NAME_OLD] = $prepared;
        }

        return $input;
    }

    /**
     * Set original data for each field.
     *
     * @return void
     */
    protected function setFieldOriginalValue($key)
    {
        $values = $this->original[$key];

        $this->fields->each(function (Field $field) use ($values) {
            $field->setOriginal($values);
        });
    }

    /**
     * @param $record
     * @return mixed
     */
    protected function prepareRecord($record)
    {
        if ($record[static::REMOVE_FLAG_NAME] == 1) {
            return $record;
        }

        $prepared = [];

        foreach ($this->fields as $field) {
            $columns = $field->column();

            $value = $this->fetchColumnValue($record, $columns);

            if (method_exists($field, 'prepare')) {
                $value = $field->prepare($value);
            }

            if ($value != $field->original()) {
                if (is_array($columns)) {
                    foreach ($columns as $name => $column) {
                        array_set($prepared, $column, $value[$name]);
                    }
                } elseif (is_string($columns)) {
                    array_set($prepared, $columns, $value);
                }
            }
        }

        return $prepared;
    }

    /**
     * Fetch value in input data by column name.
     *
     * @param array $data
     * @param string|array $columns
     * @return array|mixed
     */
    protected function fetchColumnValue($data, $columns)
    {
        if (is_string($columns)) {
            return array_get($data, $columns);
        }

        if (is_array($columns)) {
            $value = [];
            foreach ($columns as $name => $column) {
                if (!array_has($data, $column)) {
                    continue;
                }
                $value[$name] = array_get($data, $column);
            }

            return $value;
        }
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $this->fields->push($field);

        return $this;
    }

    /**
     * Get fields of this form.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Get relation name of this form.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|string
     */
    public function getRelationName()
    {
        return $this->relation;
    }

    /**
     * Fill data to all fields in form.
     *
     * @param array $data
     *
     * @return $this
     */
    public function fill(array $data)
    {
        $this->fields->each(function (Field $field) use ($data) {
            $field->fill($data);
        });

        return $this;
    }

    /**
     * Set form element name for original records.
     *
     * @param string $pk
     *
     * @return $this
     */
    public function setElementNameForOriginal($pk)
    {
        $this->fields->each(function (Field $field) use ($pk) {
            $column = $field->column();

            if (is_array($column)) {
                $name = array_map(function ($col) use ($pk) {
                    return "{$this->relation}[".static::UPDATE_KEY_NAME_OLD."][$pk][$col]";
                }, $column);
            } else {
                $name = "{$this->relation}[".static::UPDATE_KEY_NAME_OLD."][$pk][$column]";
            }

            $field->setElementName($name);
        });

        return $this;
    }

    /**
     * Set form element name for added form elements.
     *
     * @return $this
     */
    public function setElementNameForNew()
    {
        $this->fields->each(function (Field $field) {
            $column = $field->column();

            if (is_array($column)) {
                $name = array_map(function ($col) {
                    return "{$this->relation}[".static::UPDATE_KEY_NAME_NEW."][$col][_counter_]";
                }, $column);
            } else {
                $name = "{$this->relation}[".static::UPDATE_KEY_NAME_NEW."][$column][_counter_]";
            }

            $field->setElementName($name);
        });

        return $this;
    }

    /**
     * Update relation data with input data.
     *
     * @param array $input
     */
    public function update(array $input)
    {
        $this->updateMany(array_get($input, static::UPDATE_KEY_NAME_OLD, []));

        $this->createMany(array_get($input, static::UPDATE_KEY_NAME_NEW, []));
    }

    /**
     * Update an array of new instances of the related model.
     *
     * @param array $old
     *
     * @return void
     */
    protected function updateMany(array $old)
    {
        if (empty($old)) {
            return;
        }

        $ids = $updates = [];
        foreach ($old as $pk => $value) {
            if ($value[static::REMOVE_FLAG_NAME] == 1) {
                $ids[] = $pk;
            } else {
                $updates[$pk] = $value;
            }
        }

        $this->performDestroyMany($ids);

        $this->performUpdateMany($updates);
    }

    /**
     * Perform destroy of many old records.
     *
     * @param array $removes
     *
     * @return void
     */
    protected function performDestroyMany(array $removes)
    {
        if (!empty($removes)) {
            $this->relation->getRelated()->destroy($removes);
        }
    }

    /**
     * Perform update of many old records.
     *
     * @param array $updates
     *
     * @return void
     */
    protected function performUpdateMany(array $updates)
    {
        if (empty($updates)) {
            return;
        }

        $this->relation->find(array_keys($updates))
            ->each(function (Model $model) use ($updates) {

                $update = $updates[$model->{$model->getKeyName()}];

                $update = array_map(function ($item) {

                    if (is_array($item)) {
                        $item = implode(',', $item);
                    }

                    return $item;
                }, $update);

                $model->update($update);
            });
    }

    /**
     * Create an array of new instances of the related model.
     *
     * @param  array  $input
     *
     * @return array
     */
    protected function createMany(array $input)
    {
        if (empty($input)) {
            return;
        }

        collect($input)->reject(function($record) {

            return $record[static::REMOVE_FLAG_NAME] == 1;
        })->map(function ($record) {
            unset($record[static::REMOVE_FLAG_NAME]);

            return $record;
        })->reject(function ($record) {

            return empty(array_filter($record));
        })->map(function ($record) {
            $record = array_map(function ($item) {
                if (is_array($item)) {
                    $item = implode(',', $item);
                }

                return $item;
            }, $record);

            return $record;
        })->pipe(function ($records) {

            $this->relation->createMany($records->all());
        });
    }

    /**
     * Format input data into valid records.
     *
     * @param array $input
     *
     * @return Collection
     */
    protected function formatInputArray($input)
    {
        $keys = array_keys($input);
        $records = new Collection();

        foreach (range(0, count(current($input)) - 1) as $index) {
            $records->push(
                array_combine($keys, data_get($input, "*.$index"))
            );
        }

        return $records;
    }

    /**
     * Get form html without script.
     *
     * @return string
     */
    public function getFormHtml()
    {
        $html = '';

        foreach ($this->fields() as $field) {
            $html .= $field->render();

            if ($script = $field->getScript()) {
                $this->scripts[] = $field->getScript();

                array_pop(Admin::$script);
            }
        }

        return $html;
    }

    /**
     * Get form script as string.
     *
     * @return string
     */
    public function getFormScript()
    {
        return implode("\r\n",  $this->scripts);
    }

    /**
     * Add nested-form fields dynamically.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return $this|Field
     */
    public function __call($method, $arguments)
    {
        if ($className = Form::findFieldClass($method)) {
            $column = array_get($arguments, 0, '');

            $element = new $className($column, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }

        return $this;
    }
}
