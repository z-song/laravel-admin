<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NestedForm
{
    protected $relation;

    protected $fields;

    public function __construct($relation)
    {
        $this->relation = $relation;

        $this->fields = new Collection();
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

    public function fields()
    {
        return $this->fields;
    }

    public function __call($method, $arguments)
    {
        if ($className = Form::findFieldClass($method)) {
            $column = array_get($arguments, 0, '');

            $element = new $className($column, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }
    }

    public function getRelationName()
    {
        return $this->relation;
    }

    public function fill($data)
    {
        $this->fields->each(function (Field $field) use ($data) {
            $field->fill($data);
            $column = $field->column();
            $field->setName("{$this->relation}[old][{$data['id']}][$column]");
        });

        return $this;
    }

    public function setNameForNew()
    {
        $this->fields->each(function (Field $field) {
            $column = $field->column();
            $field->setName("{$this->relation}[new][$column][]");
        });
    }

    public function update($prepared)
    {
        $old = array_get($prepared, 'old', []);
        $new = array_get($prepared, 'new', []);

        $this->updateOld($old);
        $this->updateNew($new);
    }

    protected function updateOld($old)
    {
        if (empty($old)) {
            return;
        }

        $removes = $updates = [];
        foreach ($old as $pk => $value) {
            if ($value['_remove'] == 1) {
                $removes[] = $pk;
            } else {
                $updates[$pk] = $value;
            }
        }

        if (!empty($removes)) {
            $this->relation->getRelated()->destroy($removes);
        }

        if (!empty($updates)) {
            $this->performHasManyUpdate($updates);
        }
    }

    protected function performHasManyUpdate($updates)
    {
        $this->relation->find(array_keys($updates))->each(function (Model $model) use ($updates) {
            $model->update($updates[$model->{$model->getKeyName()}]);
        });
    }

    public function updateNew($new)
    {
        if (empty($new)) {
            return;
        }

        $first = current($new);
        $count = count($first);

        $saves = [];

        foreach (range(0, $count-1) as $index) {
            foreach ($new as $key => $value) {
                $saves[$index][$key] = array_get($new, "$key.$index");
            }
        }

        array_pop($saves);

        $saves = collect($saves)->reject(function($save) {
            return $save['_remove'] == 1;
        })->map(function ($save) {
            unset($save['_remove']);

            return $save;
        });

        if (!empty($saves)) {
            $this->relation->createMany($saves->toArray());
        }
    }
}
