<?php
namespace Encore\Admin;

use Closure;

use Encore\Admin\Form\Field;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\Relation;

class Form {

    /**
     * Database model id of the form entity.
     *
     * @var
     */
    protected $id;

    /**
     * Eloquent model of the form.
     *
     * @var $model
     */
    protected $model;

    /**
     * Form builder.
     *
     * @var \Closure
     */
    protected $builder;

    /**
     * Is this form builded
     *
     * @var bool
     */
    protected $builded = false;

    protected $options = [
        'title' => ''
    ];

    const MODE_VIEW     = 'view';
    const MODE_EDIT     = 'edit';
    const MODE_CREATE   = 'create';

    /**
     * Form action mode, could be create|view|edit.
     *
     * @var string
     */
    protected $mode = 'create';

    /**
     * Collection of all fields in form.
     *
     * @var Collection
     */
    protected $fields;

    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * @param $model
     * @param callable $callable
     */
    public function __construct($model, Closure $callable)
    {
        $this->model = $model;

        $this->fields = new Collection();

        $this->builder = $callable;
    }

    /**
     * Generate a edit form.
     *
     * @param $id
     * @return $this
     */
    public function edit($id)
    {
        $this->mode = self::MODE_EDIT;

        $this->buildForm();

        $this->setFieldValue($id);

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function view($id)
    {
        $this->mode = self::MODE_VIEW;

        $this->buildForm();

        $this->setFieldValue($id);

        return $this;
    }

    /**
     * Destroy entity and remove files.
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $this->buildForm();

        $this->removeFiles($id);

        return $this->model->find($id)->delete();
    }

    /**
     * Remove files or images in record.
     *
     * @param $id
     */
    protected function removeFiles($id)
    {
        $data = $this->model->with($this->getRelations())
            ->findOrFail($id)->toArray();

        $this->fields()->filter(function($field) {

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

        $inserts    = array_filter($data, 'is_string');
        $relations  = array_filter($data, 'is_array');

        DB::transaction(function() use ($inserts, $relations) {

            $inserts = $this->prepareInsert($inserts);

            foreach ($inserts as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

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
        });

        return redirect($this->resource());
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

        $this->setOriginal();

        $updates   = array_filter($data, 'is_string');
        $relations = array_filter($data, 'is_array');

        DB::transaction(function() use ($updates, $relations) {

            $updates = $this->prepareUpdate($updates);

            foreach ($updates as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

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
                        $relation->getRelated()->update($prepared[$name]);
                        break;
                }
            }
        });

        return redirect($this->resource());
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

        foreach ($this->fields() as $field) {

            $columns = $field->column();

            $value = $this->getDataByColumn($updates, $columns);

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

    protected function getDataByColumn($data, $columns)
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
     * Prepare input data for insert.
     *
     * @param $inserts
     * @return array
     */
    public function prepareInsert($inserts)
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
     * Find field object by column.
     *
     * @param $column
     * @return mixed
     */
    protected function getFieldByColumn($column)
    {
        return $this->fields()->first(
            function ($index, $field) use ($column) {
                return $field->column() == $column;
            }
        );
    }

    /**
     * Set original data for each field.
     */
    protected function setOriginal()
    {
        $values = $this->model->toArray();

        $this->fields()->each(function($field) use ($values) {
            $field->setOriginal($values);
        });
    }

    /**
     * Determine if model has column.
     *
     * @param $model
     * @param $column
     * @return mixed
     */
    protected function hasColumn($model, $column)
    {
        return Schema::hasColumn($model->getTable(), $column);
    }

    /**
     * Set all fields value in form.
     *
     * @param $id
     */
    protected function setFieldValue($id)
    {
        $this->id = $id;

        $relations = $this->getRelations();

        $this->model = $this->model->with($relations)->findOrFail($id);

        $data = $this->model->toArray();

        $this->fields()->each(function($field) use ($data) {
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
        $this->buildForm();

        $data = $rules = [];

        foreach ($this->fields() as $field) {
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
     * Get all relations of model from builder.
     *
     * @return array
     */
    public function getRelations()
    {
        $relations = $columns = [];

        foreach($this->fields as $field) {
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

    public function buildForm()
    {
        if($this->builded) return;

        call_user_func($this->builder, $this);

        $this->builded = true;
    }

    public function options($options = [])
    {
        if(empty($options)) {
            return $this->options;
        }

        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    public function open($options = [])
    {
        if($this->mode == self::MODE_EDIT) {

            $attributes['action'] = $this->resource() . '/' . $this->id;
            $attributes['method'] = array_get($options, 'method', 'post');
            $attributes['accept-charset'] = 'UTF-8';
            $attributes['enctype'] = 'multipart/form-data';

            $this->hidden('_method')->value('PUT');
        }

        if($this->mode == self::MODE_CREATE) {

            $attributes['action'] = $this->resource();
            $attributes['method'] = array_get($options, 'method', 'post');
            $attributes['accept-charset'] = 'UTF-8';
            $attributes['enctype'] = 'multipart/form-data';
        }

        $attributes['class'] = array_get($options, 'class');

        foreach($attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return '<form '.join(' ', $html).'>';
    }

    public function close()
    {
        return '</form>';
    }

    public function resource()
    {
        $route = app('router')->current();
        $prefix = $route->getPrefix();

        $resource = trim(str_replace($prefix, '', $route->getUri()), '/') . '/';

        return Admin::url(substr($resource, 0, strpos($resource, '/')));
    }

    public function submit()
    {
        if($this->mode == self::MODE_VIEW) {
            return;
        }

        return '<button type="submit" class="btn btn-info pull-right">提交</button>';
    }

    public function __call($method, $arguments)
    {
        $className = __NAMESPACE__ . '\\Form\\Field\\' . ucfirst($method);

        if(class_exists($className)) {

            $column = $arguments[0];

            $element = new $className($column, array_slice($arguments, 1));

            $this->fields()->push($element);

            return $element;
        }
    }

    public function render()
    {
        if(! $this->builded) {
            $this->buildForm();
        }

        return view('admin::form', ['form' => $this])->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}