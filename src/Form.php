<?php
namespace Encore\Admin;

use Closure;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\Relation;

class Form {

    protected $id;

    protected $model;

    protected $builder;

    protected $builded = false;

    protected $options = [
        'title' => ''
    ];

    const MODE_VIEW     = 'view';
    const MODE_EDIT     = 'edit';
    const MODE_CREATE   = 'create';

    protected $relations = [];

    /**
     * Form action mode, could be create|view|edit.
     *
     * @var string
     */
    protected $mode = 'create';

    protected $fields;

    public function __construct($model, Closure $callable)
    {
        $this->model = $model;

        $this->fields = new Collection();

        $this->builder = $callable;
    }

    public function edit($id)
    {
        $this->mode = self::MODE_EDIT;

        $this->build();

        $this->fillData($id);

        return $this;
    }

    public function view($id)
    {
        $this->mode = self::MODE_VIEW;

        $this->build();

        $this->fillData($id);

        return $this;
    }

    public function create()
    {
        $data = Input::all();

        if( ! $this->validate($data)) {
            return back()->withInput()->withErrors($this->errors);
        }

        $inserts    = array_filter($data, 'is_string');
        $relations  = array_filter($data, 'is_array');

        DB::transaction(function() use ($inserts, $relations) {

            foreach($inserts as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            foreach($relations as $name => $values) {

                if( ! method_exists($this->model, $name)) {
                    continue;
                }

                $relation = $this->model->$name();

                switch (get_class($relation)) {
                    case \Illuminate\Database\Eloquent\Relations\BelongsToMany::class :
                        $relation->attach($values);
                        break;
                    case \Illuminate\Database\Eloquent\Relations\HasOne::class :
                        $related = $relation->getRelated();
                        foreach($values as $column => $value) {
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
            return back()->withInput()->withErrors($this->errors);
        }

        $this->model = $this->model->with($this->getRelations())->findOrFail($id);

        $this->setOldValue();

        $updates   = array_filter($data, 'is_string');
        $relations = array_filter($data, 'is_array');

        DB::transaction(function() use ($updates, $relations) {

            $updates = $this->prepare($updates);

            $this->model->update($updates);

            foreach($relations as $name => $values) {

                $values = array_combine(
                    array_keys($values),
                    $this->prepare([$name => $values])
                );

                if( ! method_exists($this->model, $name)) {
                    continue;
                }

                $relation = $this->model->$name();

                switch (get_class($relation)) {
                    case \Illuminate\Database\Eloquent\Relations\BelongsToMany::class :
                        $relation->sync($values);
                        break;
                    case \Illuminate\Database\Eloquent\Relations\HasOne::class :
                        $relation->update($values);
                        break;
                }
            }
        });

        return redirect($this->resource());
    }

    public function prepare($updates)
    {
        $updates = Arr::dot($updates);

        foreach($updates as $column => &$value) {
            $field = $this->fields()->first(
                function ($index, $field) use ($column) {
                    return $field->column() == $column;
                }
            );

            if (!is_null($field) && method_exists($field, 'prepare')) {
                $value = $field->prepare($value);
            }
        }

        return $updates;
    }

    public function setOldValue()
    {
        $values = $this->model->toArray();

        foreach($this->fields() as $field) {
            $field->setOriginal($values);
        }
    }

    protected function hasColumn($model, $column)
    {
        return Schema::hasColumn($model->getTable(), $column);
    }

    protected function fillData($id)
    {
        $relations = $this->getRelations();

        $this->model = $this->model->with($relations)->findOrFail($id);

        $data = $this->model->toArray();

        foreach($this->fields() as $field) {
            $field->fill($data);
        }

        $this->id = $id;
    }

    protected function validate($input)
    {
        $this->build();

        $data = $rules = [];
        foreach($this->fields() as $field) {
            if( ! method_exists($field, 'rules') || ! $rule = $field->rules()) {
                continue;
            }

            $columns = $field->column();

            if(is_string($columns)) {
                $data[$field->label()] = Arr::get($input, $columns);

                $rules[$field->label()] = $rule;
            }
            if(is_array($columns)) {
                foreach($columns as $key => $column) {
                    $data[$field->label().$key] = Arr::get($input, $column);

                    $rules[$field->label().$key] = $rule;
                }
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $this->errors = $validator->messages();

            return false;
        }

        return true;
    }

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

    public function build()
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

    public function addField($field)
    {
//        $columns = (array) $field->column();
//
//        foreach($columns as $column) {
//            if(strpos($column, '.') !== false) {
//                list($relationName, $relationColumn) = explode('.', $column);
//                if(method_exists($this->model, $relationName) &&
//                    (($relation = $this->model->$relationName()) instanceof Relation)
//                ) {
//                    $this->model->setRelation($relationName, $relation);
//                }
//            }
//        }

        $this->fields[] = $field;
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
        }

        $attributes['class'] = array_get($options, 'class');

        foreach($attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return '<form '.join(' ', $html).'>';
    }

    public function resource()
    {
        $route = app('router')->current();
        $prefix = $route->getPrefix();

        $resource = trim(str_replace($prefix, '', $route->getUri()), '/') . '/';

        return Admin::url(substr($resource, 0, strpos($resource, '/')));
    }

    public function close()
    {
        return '</form>';
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

            $this->addField($element);

            return $element;
        }
    }

    public function render()
    {
        if(! $this->builded) {
            $this->build();
        }

        return view('admin::form', ['form' => $this])->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}