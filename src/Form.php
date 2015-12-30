<?php
namespace Encore\Admin;

use Closure;

use Illuminate\Support\Facades\Input;
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
        $this->build();

        $this->fillData($id);

        $this->mode = self::MODE_EDIT;

        return $this;
    }

    public function view($id)
    {
        $this->build();

        $this->fillData($id);

        $this->mode = self::MODE_VIEW;

        return $this;
    }

    public function create()
    {
        $data = Input::all();

        if( ! $this->validate($data)) {
            return back()->withInput()->withErrors($this->errors);
        }

        $self       = array_filter($data, 'is_string');
        $relations  = array_filter($data, 'is_array');

        DB::transaction(function() use ($self, $relations) {

            $this->model->create($self);

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
                        foreach($values as $column => $value) {
                            $relation->setAttribute($column, $value);
                        }
                        $relation->save();
                        break;
                }
            }
        });

        return redirect()->back();
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

        $self       = array_filter($data, 'is_string');
        $relations  = array_filter($data, 'is_array');

        $model = $this->model->find($id);

        DB::transaction(function() use ($model, $self, $relations) {

            $model->update($self);

            foreach($relations as $name => $values) {

                if( ! method_exists($model, $name)) {
                    continue;
                }

                $relation = $model->$name();

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

        return redirect()->back();
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

            $rules[$field->label()] = $rule;

            $columns = $field->column();
            if(is_string($columns)) {
                $data[$field->label()] = Arr::get($input, $columns);
            }
            if(is_array($columns)) {
                foreach($columns as $column) {
                    $data[$column] = Arr::get($input, $column);
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

        $this->open();

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
            $resources = app('router')->current()->parameterNames();

            $attributes['action'] = Admin::url(current($resources)) . '/' . $this->id;
            $attributes['method'] = array_get($options, 'method', 'post');
            $attributes['accept-charset'] = 'UTF-8';

            $this->hidden('_method')->value('PUT');
        }

        if($this->mode == self::MODE_CREATE) {
            $resource = app('router')->current()->getUri();
            $resourceName = substr($resource, 0, strrpos($resource, '/'));

            $attributes['action'] = Admin::url($resourceName);
            $attributes['method'] = array_get($options, 'method', 'post');
            $attributes['accept-charset'] = 'UTF-8';
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