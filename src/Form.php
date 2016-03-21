<?php
namespace Encore\Admin;

use Closure;
use Encore\Admin\Form\Builder;
use Encore\Admin\Form\Field;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Form
 *
 * @method Field\Text           text($column, $label = '')
 * @method Field\Checkbox       checkbox($column, $label = '')
 * @method Field\Radio          radio($column, $label = '')
 * @method Field\Select         select($column, $label = '')
 * @method Field\MultipleSelect multipleSelect($column, $label = '')
 * @method Field\Textarea       textarea($column, $label = '')
 * @method Field\Hidden         hidden($column, $label = '')
 *
 * @method Field\Id             id($column, $label = '')
 * @method Field\Ip             ip($column, $label = '')
 * @method Field\Url            url($column, $label = '')
 * @method Field\Color          color($column, $label = '')
 * @method Field\Email          email($column, $label = '')
 * @method Field\Mobile         mobile($column, $label = '')
 * @method Field\Slider         slider($column, $label = '')
 * @method Field\Map            map($latitude, $longitude, $label = '')
 *
 * @method Field\Editor         editor($column, $label = '')
 * @method Field\Markdown       markdown($column, $label = '')
 *
 * @method Field\File           file($column, $label = '')
 * @method Field\Image          image($column, $label = '')
 *
 * @method Field\Date           date($column, $label = '')
 * @method Field\Datetime       datetime($column, $label = '')
 * @method Field\Time           time($column, $label = '')
 * @method Field\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DateTimeRange  dateTimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Options        options(array $options)
 *
 * @package Encore\Admin
 */
class Form {

    /**
     * Eloquent model of the form.
     *
     * @var $model
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
     * @param \$model
     * @param \Closure $callback
     */
    public function __construct($model, Closure $callback)
    {
        $this->model = $model;

        $this->builder = new Builder($this);

        $callback($this);
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
     * Generate a edit form.
     *
     * @param $id
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
     * @return mixed
     */
    public function destroy($id)
    {
        $this->deleteFilesAndImages($id);

        return $this->model->find($id)->delete();
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

        $this->builder->fields()->filter(function($field) {

            return $field instanceof Field\File;

        })->each(function($field) use ($data) {

            $field->setOriginal($data);

            $field->destroy();
        });
    }

    /**
     * Create a new record.
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        $data = Input::all();

        if( ! $this->validate($data)) {
            return back()->withInput()->withErrors($this->validator->messages());
        }

        $this->prepare($data, $this->saving);

        DB::transaction(function() {

            $inserts = $this->prepareInsert($this->updates);

            foreach ($inserts as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->saveRelation($this->relations);
        });

        $this->complete($this->saved);

        return redirect($this->resource());
    }

    /**
     * Prepare input data for insert or update.
     *
     * @param array $data
     * @param callable $callback
     */
    protected function prepare($data = [], Closure $callback = null)
    {
        $this->inputs = $data;

        if($callback instanceof Closure) {
            $callback($this);
        }

        $this->updates = array_filter($this->inputs, function($val) {
            return is_string($val) or ($val instanceof UploadedFile);
        });
        $this->relations = array_filter($this->inputs, 'is_array');
    }

    /**
     * Callback after saving a Model
     * @param Closure|null $callback
     */
    protected function complete(Closure $callback = null)
    {
        if($callback instanceof Closure) {
            $callback($this);
        }
    }

    /**
     * Save relations data.
     *
     * @param array $relations
     * @return void
     */
    protected function saveRelation($relations)
    {
        foreach ($relations as $name => $values) {

            if( ! method_exists($this->model, $name)) {
                continue;
            }

            $values = $this->prepareInsert([$name => $values]);

            $relation = $this->model->$name();

            switch (get_class($relation)) {
                case \Illuminate\Database\Eloquent\Relations\BelongsToMany::class :
                    $relation->attach($values[$name]);
                    break;
                case \Illuminate\Database\Eloquent\Relations\HasOne::class :
                    $related = $relation->getRelated();
                    foreach($values[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }

                    $relation->save($related);
                    break;
            }
        }
    }

    /**
     * @param $id
     * @param $data
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update($id, $data)
    {
        if( ! $this->validate($data)) {
            return back()->withInput()->withErrors($this->validator->messages());
        }

        $this->model = $this->model->with($this->getRelations())->findOrFail($id);

        $this->setFieldOriginalValue();

        $this->prepare($data, $this->saving);

        DB::transaction(function() {

            $updates = $this->prepareUpdate($this->updates);

            foreach ($updates as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->updateRelation($this->relations);
        });

        $this->complete($this->saved);

        return redirect($this->resource());
    }

    /**
     * Update relation data.
     *
     * @param array $relations
     * @return void
     */
    protected function updateRelation($relations)
    {
        foreach($relations as $name => $values) {

            if( ! method_exists($this->model, $name)) {
                continue;
            }

            $prepared = $this->prepareUpdate([$name => $values]);

            if(empty($prepared)) continue;

            $relation = $this->model->$name();

            switch (get_class($relation)) {
                case \Illuminate\Database\Eloquent\Relations\BelongsToMany::class :

                    $relation->sync($prepared[$name]);
                    break;
                case \Illuminate\Database\Eloquent\Relations\HasOne::class :

                    foreach($prepared[$name] as $column => $value) {
                        $this->model->$name->setAttribute($column, $value);
                    }

                    $this->model->$name->save();
                    break;
            }
        }
    }

    /**
     * Prepare input data for update.
     *
     * @param $updates
     * @return array
     */
    protected function prepareUpdate($updates)
    {
        $prepared = [];

        foreach ($this->builder->fields() as $field) {

            $columns = $field->column();

            $value = static::getDataByColumn($updates, $columns);

            if(empty($value)) continue;

            method_exists($field, 'prepare') && $value = $field->prepare($value);

            if($value != $field->original()) {

                if(is_array($columns)) {
                    foreach ($columns as $name => $column) {
                        Arr::set($prepared, $column, $value[$name]);
                    }
                } else if (is_string($columns)) {
                    Arr::set($prepared, $columns, $value);
                }
            }
        }

        return $prepared;
    }

    /**
     * Prepare input data for insert.
     *
     * @param $inserts
     * @return array
     */
    protected function prepareInsert($inserts)
    {
        $first = current($inserts);

        if (is_array($first) && Arr::isAssoc($first)) {
            $inserts = Arr::dot($inserts);
        }

        foreach ($inserts as $column => $value) {

            if(is_null($field = $this->getFieldByColumn($column))) {
                unset($inserts[$column]);
                continue;
            }

            if (method_exists($field, 'prepare')) {
                $inserts[$column] = $field->prepare($value);
            }
        }

        $prepared = [];

        foreach ($inserts as $key => $value) {
            Arr::set($prepared, $key, $value);
        }

        return $prepared;
    }

    /**
     * Set saving callback.
     *
     * @param callable $callback
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
     * @return void
     */
    public function saved(Closure $callback)
    {
        $this->saved = $callback;
    }

    /**
     * @param array $data
     * @param string|array $columns
     * @return array|mixed
     */
    protected static function getDataByColumn($data, $columns)
    {
        if (is_string($columns)) {
            return Arr::get($data, $columns);
        }

        if (is_array($columns)) {
            $value = [];
            foreach ($columns as $name => $column) {
                if(! Arr::has($data, $column)) continue;
                $value[$name] = Arr::get($data, $column);
            }

            return $value;
        }
    }

    /**
     * Find field object by column.
     *
     * @param $column
     * @return mixed
     */
    protected function getFieldByColumn($column)
    {
        return $this->builder->fields()->first(
            function ($index, $field) use ($column) {

                if(is_array($field->column())) {
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

        $this->builder->fields()->each(function($field) use ($values) {
            $field->setOriginal($values);
        });
    }

    /**
     * Set all fields value in form.
     *
     * @param $id
     * @return void
     */
    protected function setFieldValue($id)
    {
        $relations = $this->getRelations();

        $this->model = $this->model->with($relations)->findOrFail($id);

        $data = $this->model->toArray();

        $this->builder->fields()->each(function($field) use ($data) {
            $field->fill($data);
        });
    }

    /**
     * Validate input data.
     *
     * @param $input
     * @return bool
     */
    protected function validate($input)
    {
        $data = $rules = [];

        foreach ($this->builder->fields() as $field) {
            if( ! method_exists($field, 'rules') || ! $rule = $field->rules()) {
                continue;
            }

            $columns = $field->column();

            if(is_string($columns)) {
                $data[$field->label()] = Arr::get($input, $columns);
                $rules[$field->label()] = $rule;
            }

            if(is_array($columns)) {
                foreach ($columns as $key => $column) {
                    $data[$field->label().$key] = Arr::get($input, $column);
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

        foreach($this->builder->fields() as $field) {
            $columns[] = $field->column();
        }

        foreach(Arr::flatten($columns) as $column) {
            if(Str::contains($column, '.')) {
                list($relation) = explode('.', $column);

                if(method_exists($this->model, $relation) &&
                    $this->model->$relation() instanceof Relation
                ) {
                    $relations[] = $relation;
                }
            } elseif(method_exists($this->model, $column)) {
                $relations[] = $column;
            }
        }

        return array_unique($relations);
    }

    /**
     * Get current resource route url.
     *
     * @return string
     */
    public function resource()
    {
        $route = app('router')->current();
        $prefix = $route->getPrefix();

        $resource = trim(preg_replace("#$prefix#", '', $route->getUri(), 1), '/') . '/';

        return "/$prefix/" . substr($resource, 0, strpos($resource, '/'));
    }

    /**
     * Render the form contents.
     *
     * @return string
     */
    public function render()
    {
        return $this->builder->build();
    }

    /**
     * Get or set input data.
     *
     * @param string $key
     * @param null $value
     * @return array|mixed
     */
    public function input($key, $value = null)
    {
        if(is_null($value)) {
            return Arr::get($this->inputs, $key);
        }

        return Arr::set($this->inputs, $key, $value);
    }

    /**
     * Getter.
     *
     * @param string $name
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
     * @param array $arguments
     * @return \Encore\Admin\Field
     */
    public function __call($method, $arguments)
    {
        $className = __NAMESPACE__ . '\\Form\\Field\\' . ucfirst($method);

        if(class_exists($className)) {

            $column = $arguments[0];

            $element = new $className($column, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }
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
