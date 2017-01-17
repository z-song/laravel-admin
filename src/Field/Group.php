<?php

namespace Encore\Admin\Field;

use Encore\Admin\Admin;
use Encore\Admin\Field;
use Illuminate\Support\Collection;

class Group
{
    /**
     * @var Collection
     */
    protected $fields;

    /**
     * nested form index, key.
     *
     * @var
     */
    protected $key;

    /**
     * relation name.
     *
     * @var
     */
    protected $relationName;

    /**
     * Group scripts.
     *
     * @var
     */
    protected $scripts = [];

    /**
     * template.
     *
     * @var array
     */
    protected $template = [];

    /**
     * owner, the top level with model().
     *
     * @var
     */
    protected $owner = null;

    const DEFAULT_KEY_NAME = '_key_';

    const REMOVE_FLAG_NAME = '_remove_';

    const REMOVE_FLAG_CLASS = 'fom-removed';

    public function __construct($relationName, $key = '_key_')
    {
        $this->relationName = $relationName;

        $this->key = $key;

        $this->fields = new Collection();
    }

    /**
     * Set Group key.
     *
     * @param $key
     * author Edwin Hui
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Fill data to the field.
     *
     * @param $data
     *
     * @return void
     */
    public function fill($data)
    {
        $this->fields->each(function (Field $field) use ($data) {
            $field->fill($data);
        });

        return $this;
    }

    public function getRelationName()
    {
        return $this->relationName;
    }

    /**
     * @param DataField $field
     *
     * @return $this
     */
    public function pushField(DataField $field)
    {
        $field->setGroup($this);

        $this->fields->push($field);

        return $this;
    }

    public function fields()
    {
        return $this->fields;
    }

    /**
     * @param $owner
     *
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get form html without script.
     *
     * @return string
     */
    public function buildTemplate()
    {
        $html = '';

        foreach ($this->fields() as $field) {
            $html .= $field->render();

            if ($script = $field->getScript()) {
                $this->scripts[] = $field->getScript();

                array_pop(Admin::$script);
            }
        }

        $this->template['html'] = $html;
        $this->template['script'] = implode("\r\n", $this->scripts);

        return $this;
    }

    /**
     * Get form script as string.
     *
     * @return string
     */
    public function getTemplateScript()
    {
        return $this->template['script'];
    }

    /**
     * Get form html as string.
     *
     * @return string
     */
    public function getTemplateHtml()
    {
        return $this->template['html'];
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
        $validators = [];
        foreach ($this->fields as $field) {
            $validators[] = $field->getValidator($input);
        }

        return $validators;
    }

    /**
     * Add nested-form fields dynamically.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this|Field
     */
    public function __call($method, $arguments)
    {
        if ($className = Field::findFieldClass($method)) {
            $column = array_get($arguments, 0, '');

//            $column = "{$this->relationName}.{$this->key}.{$column}";

            $element = new $className($column, array_slice($arguments, 1));

            $element->setColumnName("{$this->relationName}.{$this->key}.{$column}")
                    ->setElementClass("{$this->relationName}_{$column}")
                    ->setErrorKey("{$this->relationName}_{$this->key}_{$column}");

            $this->pushField($element);

            return $element;
        }

        return $this;
    }
}
