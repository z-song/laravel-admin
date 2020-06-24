<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as Relation;
use Illuminate\Support\Arr;

class BelongsToMany extends BelongsTo
{
    /**
     * Other key for many-to-many relation.
     *
     * @var string
     */
    protected static $otherKey = [];

    /**
     * Get other key for this many-to-many relation.
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getOtherKey()
    {
        if (isset(static::$otherKey[$this->getName()])) {
            return static::$otherKey[$this->getName()];
        }

        $model = $this->getGrid()->model()->getOriginalModel();

        if (is_callable([$model, $this->getName()]) &&
            ($relation = $model->{$this->getName()}()) instanceof Relation
        ) {
            /* @var Relation $relation */
            $fullKey = $relation->getQualifiedRelatedPivotKeyName();
            $fullKeyArray = explode('.', $fullKey);

            return static::$otherKey[$this->getName()] = end($fullKeyArray);
        }

        throw new \Exception('Column of this field must be a `BelongsToMany` relation.');
    }

    /**
     * @throws \Exception
     *
     * @return false|string|void
     */
    protected function getOriginalData()
    {
        $relations = $this->getColumn()->getOriginal();

        if (is_string($relations)) {
            $data = explode(',', $relations);
        }

        if (!is_array($relations)) {
            return;
        }

        $first = current($relations);

        if (is_null($first)) {
            $data = null;

        // MultipleSelect value store as an ont-to-many relationship.
        } elseif (is_array($first)) {
            foreach ($relations as $relation) {
                $data[] = Arr::get($relation, "pivot.{$this->getOtherKey()}");
            }

            // MultipleSelect value store as a column.
        } else {
            $data = $relations;
        }

        return json_encode($data);
    }
}
