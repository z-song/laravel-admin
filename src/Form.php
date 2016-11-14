<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Exception\Handle;
use Encore\Admin\Form\Builder;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\Field\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\EloquentSortable\Sortable;

/**
 * Class Form.
 *
 * @method Field\Text           text($column, $label = '')
 * @method Field\Checkbox       checkbox($column, $label = '')
 * @method Field\Radio          radio($column, $label = '')
 * @method Field\Select         select($column, $label = '')
 * @method Field\MultipleSelect multipleSelect($column, $label = '')
 * @method Field\Textarea       textarea($column, $label = '')
 * @method Field\Hidden         hidden($column, $label = '')
 * @method Field\Id             id($column, $label = '')
 * @method Field\Ip             ip($column, $label = '')
 * @method Field\Url            url($column, $label = '')
 * @method Field\Color          color($column, $label = '')
 * @method Field\Email          email($column, $label = '')
 * @method Field\Mobile         mobile($column, $label = '')
 * @method Field\Slider         slider($column, $label = '')
 * @method Field\Map            map($latitude, $longitude, $label = '')
 * @method Field\Editor         editor($column, $label = '')
 * @method Field\File           file($column, $label = '')
 * @method Field\Image          image($column, $label = '')
 * @method Field\Date           date($column, $label = '')
 * @method Field\Datetime       datetime($column, $label = '')
 * @method Field\Time           time($column, $label = '')
 * @method Field\Year           year($column, $label = '')
 * @method Field\Month          month($column, $label = '')
 * @method Field\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DateTimeRange  dateTimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Number         number($column, $label = '')
 * @method Field\Currency       currency($column, $label = '')
 * @method Field\Json           json($column, $label = '')
 * @method Field\HasMany        hasMany($relationName, $callback)
 * @method Field\SwitchField    switch($column, $label = '')
 * @method Field\Display        display($column, $label = '')
 * @method Field\Rate           rate($column, $label = '')
 * @method Field\Divide         divide()
 * @method Field\Password       password($column, $label = '')
 * @method Field\Decimal        decimal($column, $label = '')
 */
class Form
{
    /**
     * Eloquent model of the form.
     *
     * @var
     */
    protected $model;

    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Saving callback.
     *
     * @var Closure
     */
    protected $saving;

    /**
     * Saved callback.
     *
     * @var Closure
     */
    protected $saved;

    /**
     * Data for save to current model from input.
     *
     * @var array
     */
    protected $updates = [];

    /**
     * Data for save to model's relations from input.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Input data.
     *
     * @var array
     */
    protected $inputs = [];

    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param \$model
     * @param \Closure $callback
     */
    public function __construct($model, Closure $callback)
    {
        $this->model = $model;

        $this->builder = new Builder($this);

        $this->callable = $callback;

        $callback($this);
    }

    /**
     * Set up the form.
     */
    protected function setUp()
    {
        call_user_func($this->callable, $this);
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $field->setForm($this);

        $this->builder->fields()->push($field);

        return $this;
    }

    /**
     * @return Model
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return Builder
     */
    public function builder()
    {
        return $this->builder;
    }

    /**
     * Generate a edit form.
     *
     * @param $id
     *
     * @return $this
     */
    public function edit($id)
    {
        $this->builder->setMode(Builder::MODE_EDIT);
        $this->builder->setResourceId($id);

        $this->setFieldValue($id);

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function view($id)
    {
        $this->builder->setMode(Builder::MODE_VIEW);
        $this->builder->setResourceId($id);

        $this->setFieldValue($id);

        return $this;
    }

    /**
     * Destroy data entity and remove files.
     *
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);

        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $this->deleteFilesAndImages($id);
            $this->model->find($id)->delete();
        }

        return true;
    }

    /**
     * Remove files or images in record.
     *
     * @param $id
     */
    protected function deleteFilesAndImages($id)
    {
        $data = $this->model->with($this->getRelations())
            ->findOrFail($id)->toArray();

        $this->builder->fields()->filter(function ($field) {
            return $field instanceof Field\File;
        })->each(function (File $file) use ($data) {
            $file->setOriginal($data);

            $file->destroy();
        });
    }

    /**
     * Store a new record.
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $data = Input::all();

        if (!$this->validate($data)) {
            return back()->withInput()->withErrors($this->validator->messages());
        }

        $this->prepare($data, $this->saving);

        DB::transaction(function () {
            $inserts = $this->prepareInsert($this->updates);

            foreach ($inserts as $column => $value) {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->saveRelation($this->relations);
        });

        $this->complete($this->saved);

        return redirect($this->resource(0));
    }

    /**
     * Prepare input data for insert or update.
     *
     * @param array    $data
     * @param callable $callback
     */
    protected function prepare($data = [], Closure $callback = null)
    {
        $this->inputs = $data;

        if ($callback instanceof Closure) {
            $callback($this);
        }

        $this->relations = $this->getRelationInputs($data);

        $updates = array_except($this->inputs, array_keys($this->relations));

        $this->updates = array_filter($updates, function ($val) {
            return !is_null($val);
        });
    }

    /**
     * Get inputs for relations.
     *
     * @param array $inputs
     *
     * @return array
     */
    protected function getRelationInputs($inputs = [])
    {
        $relations = [];

        foreach ($inputs as $column => $value) {
            if (method_exists($this->model, $column)) {
                $relation = call_user_func([$this->model, $column]);

                if ($relation instanceof Relation) {
                    $relations[$column] = $value;
                }
            }
        }

        return $relations;
    }

    /**
     * Callback after saving a Model.
     *
     * @param Closure|null $callback
     *
     * @return void
     */
    protected function complete(Closure $callback = null)
    {
        if ($callback instanceof Closure) {
            $callback($this);
        }
    }

    /**
     * Save relations data.
     *
     * @param array $relations
     *
     * @return void
     */
    protected function saveRelation($relations)
    {
        foreach ($relations as $name => $values) {
            if (!method_exists($this->model, $name)) {
                continue;
            }

            $values = $this->prepareInsert([$name => $values]);

            $relation = $this->model->$name();

            switch (get_class($relation)) {
                case \Illuminate\Database\Eloquent\Relations\BelongsToMany::class:
                case \Illuminate\Database\Eloquent\Relations\MorphToMany::class:
                    $relation->attach($values[$name]);
                    break;
                case \Illuminate\Database\Eloquent\Relations\HasOne::class:
                    $related = $relation->getRelated();
                    foreach ($values[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }

                    $relation->save($related);
                    break;
            }
        }
    }

    /**
     * Handle update.
     *
     * @param int $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        $data = Input::all();

        $data = $this->handleEditable($data);

        if ($this->handleOrderable($id, $data)) {
            return response(['status' => true, 'message' => trans('admin::lang.succeeded')]);
        }

        if (!$this->validate($data)) {
            return back()->withInput()->withErrors($this->validator->messages());
        }

        $this->model = $this->model->with($this->getRelations())->findOrFail($id);

        $this->setFieldOriginalValue();

        $this->prepare($data, $this->saving);

        DB::transaction(function () {
            $updates = $this->prepareUpdate($this->updates);

            foreach ($updates as $column => $value) {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }

                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->updateRelation($this->relations);
        });

        $this->complete($this->saved);

        return redirect($this->resource(-1));
    }

    /**
     * Handle editable update.
     *
     * @param array $input
     *
     * @return array
     */
    protected function handleEditable(array $input = [])
    {
        if (array_key_exists('_editable', $input)) {
            $name = $input['name'];
            $value = $input['value'];

            array_forget($input, ['pk', 'value', 'name']);
            array_set($input, $name, $value);
        }

        return $input;
    }

    /**
     * Handle orderable update.
     *
     * @param int   $id
     * @param array $input
     *
     * @return array
     */
    protected function handleOrderable($id, array $input = [])
    {
        if (array_key_exists('_orderable', $input)) {
            $model = $this->model->find($id);

            if ($model instanceof Sortable) {
                $input['_orderable'] == 1 ? $model->moveOrderUp() : $model->moveOrderDown();

                return true;
            }
        }

        return false;
    }

    /**
     * Update relation data.
     *
     * @param array $relations
     *
     * @return void
     */
    protected function updateRelation($relations)
    {
        foreach ($relations as $name => $values) {
            if (!method_exists($this->model, $name)) {
                continue;
            }

            $prepared = $this->prepareUpdate([$name => $values]);

            if (empty($prepared)) {
                continue;
            }

            $relation = $this->model->$name();

            switch (get_class($relation)) {
                case \Illuminate\Database\Eloquent\Relations\BelongsToMany::class:
                case \Illuminate\Database\Eloquent\Relations\MorphToMany::class:
                    $relation->sync($prepared[$name]);
                    break;
                case \Illuminate\Database\Eloquent\Relations\HasOne::class:

                    $related = $this->model->$name;

                    // if related is empty
                    if (is_null($related)) {
                        $related = $relation->getRelated();
                        $related->{$relation->getForeignKey()} = $this->model->{$this->model->getKeyName()};
                    }

                    foreach ($prepared[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }

                    $related->save();
                    break;
            }
        }
    }

    /**
     * Prepare input data for update.
     *
     * @param $updates
     *
     * @return array
     */
    protected function prepareUpdate($updates)
    {
        $prepared = [];

        foreach ($this->builder->fields() as $field) {
            $columns = $field->column();

            $value = $this->getDataByColumn($updates, $columns);

            if ($value !== '' && $value !== '0' && !$field instanceof File && empty($value)) {
                continue;
            }

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
     * Prepare input data for insert.
     *
     * @param $inserts
     *
     * @return array
     */
    protected function prepareInsert($inserts)
    {
        $first = current($inserts);

        if (is_array($first) && Arr::isAssoc($first)) {
            $inserts = array_dot($inserts);
        }

        foreach ($inserts as $column => $value) {
            if (is_null($field = $this->getFieldByColumn($column))) {
                unset($inserts[$column]);
                continue;
            }

            if (method_exists($field, 'prepare')) {
                $inserts[$column] = $field->prepare($value);
            }
        }

        $prepared = [];

        foreach ($inserts as $key => $value) {
            array_set($prepared, $key, $value);
        }

        return $prepared;
    }

    /**
     * Set saving callback.
     *
     * @param callable $callback
     *
     * @return void
     */
    public function saving(Closure $callback)
    {
        $this->saving = $callback;
    }

    /**
     * Set saved callback.
     *
     * @param callable $callback
     *
     * @return void
     */
    public function saved(Closure $callback)
    {
        $this->saved = $callback;
    }

    /**
     * @param array        $data
     * @param string|array $columns
     *
     * @return array|mixed
     */
    protected function getDataByColumn($data, $columns)
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
     * Find field object by column.
     *
     * @param $column
     *
     * @return mixed
     */
    protected function getFieldByColumn($column)
    {
        return $this->builder->fields()->first(
            function ($index, Field $field) use ($column) {
                if (is_array($field->column())) {
                    return in_array($column, $field->column());
                }

                return $field->column() == $column;
            }
        );
    }

    /**
     * Set original data for each field.
     *
     * @return void
     */
    protected function setFieldOriginalValue()
    {
        $values = $this->model->toArray();

        $this->builder->fields()->each(function (Field $field) use ($values) {
            $field->setOriginal($values);
        });
    }

    /**
     * Set all fields value in form.
     *
     * @param $id
     *
     * @return void
     */
    protected function setFieldValue($id)
    {
        $relations = $this->getRelations();

        $this->model = $this->model->with($relations)->findOrFail($id);

        $data = $this->model->toArray();

        $this->builder->fields()->each(function (Field $field) use ($data) {
            $field->fill($data);
        });
    }

    /**
     * Validate input data.
     *
     * @param $input
     *
     * @return bool
     */
    protected function validate($input)
    {
        $data = $rules = [];

        foreach ($this->builder->fields() as $field) {
            if (!method_exists($field, 'rules') || !$rule = $field->rules()) {
                continue;
            }

            $columns = $field->column();

            if (is_string($columns)) {
                if (!array_key_exists($columns, $input)) {
                    continue;
                }

                $data[$field->label()] = array_get($input, $columns);
                $rules[$field->label()] = $rule;
            }

            if (is_array($columns)) {
                foreach ($columns as $key => $column) {
                    if (!array_key_exists($column, $input)) {
                        continue;
                    }
                    $data[$field->label().$key] = array_get($input, $column);
                    $rules[$field->label().$key] = $rule;
                }
            }
        }

        $this->validator = Validator::make($data, $rules);

        return $this->validator->passes();
    }

    /**
     * Get all relations of model from callable.
     *
     * @return array
     */
    public function getRelations()
    {
        $relations = $columns = [];

        foreach ($this->builder->fields() as $field) {
            $columns[] = $field->column();
        }

        foreach (array_flatten($columns) as $column) {
            if (str_contains($column, '.')) {
                list($relation) = explode('.', $column);

                if (method_exists($this->model, $relation) &&
                    $this->model->$relation() instanceof Relation
                ) {
                    $relations[] = $relation;
                }
            } elseif (method_exists($this->model, $column)) {
                $relations[] = $column;
            }
        }

        return array_unique($relations);
    }

    /**
     * Get current resource route url.
     *
     * @param int $slice
     *
     * @return string
     */
    public function resource($slice = -2)
    {
        $route = app('router')->current();

        $segments = explode('/', trim($route->getUri(), '/'));

        if ($slice != 0) {
            $segments = array_slice($segments, 0, $slice);
        }

        return '/'.implode('/', $segments);
    }

    /**
     * Render the form contents.
     *
     * @return string
     */
    public function render()
    {
        try {
            return $this->builder->render();
        } catch (\Exception $e) {
            return with(new Handle($e))->render();
        }
    }

    /**
     * Get or set input data.
     *
     * @param string $key
     * @param null   $value
     *
     * @return array|mixed
     */
    public function input($key, $value = null)
    {
        if (is_null($value)) {
            return array_get($this->inputs, $key);
        }

        return array_set($this->inputs, $key, $value);
    }

    /**
     * Getter.
     *
     * @param string $name
     *
     * @return array|mixed
     */
    public function __get($name)
    {
        return $this->input($name);
    }

    /**
     * Setter.
     *
     * @param string $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->input($name, $value);
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field|void
     */
    public function __call($method, $arguments)
    {
        if ($className = static::findFieldClass($method)) {
            $column = array_get($arguments, 0, ''); //[0];

            $element = new $className($column, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }
    }

    public static function findFieldClass($method)
    {
        $className = __NAMESPACE__.'\\Form\\Field\\'.ucfirst($method);

        if (class_exists($className)) {
            return $className;
        }

        if ($method == 'switch') {
            return __NAMESPACE__.'\\Form\\Field\\SwitchField';
        }

        return false;
    }

    /**
     * Render the contents of the form when casting to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
