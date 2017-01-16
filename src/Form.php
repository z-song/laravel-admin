<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Exception\Handle;
use Encore\Admin\Form\Builder;
use Encore\Admin\Field;
use Encore\Admin\Field\DataField;
use Encore\Admin\Field\DataField\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Spatie\EloquentSortable\Sortable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Form.
 *
 * @method DataField\Text           text($column, $label = '')
 * @method DataField\Checkbox       checkbox($column, $label = '')
 * @method DataField\Radio          radio($column, $label = '')
 * @method DataField\Select         select($column, $label = '')
 * @method DataField\MultipleSelect multipleSelect($column, $label = '')
 * @method DataField\Textarea       textarea($column, $label = '')
 * @method DataField\Hidden         hidden($column, $label = '')
 * @method DataField\Id             id($column, $label = '')
 * @method DataField\Ip             ip($column, $label = '')
 * @method DataField\Url            url($column, $label = '')
 * @method DataField\Color          color($column, $label = '')
 * @method DataField\Email          email($column, $label = '')
 * @method DataField\Mobile         mobile($column, $label = '')
 * @method DataField\Slider         slider($column, $label = '')
 * @method DataField\Map            map($latitude, $longitude, $label = '')
 * @method DataField\Editor         editor($column, $label = '')
 * @method DataField\File           file($column, $label = '')
 * @method DataField\Image          image($column, $label = '')
 * @method DataField\Date           date($column, $label = '')
 * @method DataField\Datetime       datetime($column, $label = '')
 * @method DataField\Time           time($column, $label = '')
 * @method DataField\Year           year($column, $label = '')
 * @method DataField\Month          month($column, $label = '')
 * @method DataField\DateRange      dateRange($start, $end, $label = '')
 * @method DataField\DateTimeRange  dateTimeRange($start, $end, $label = '')
 * @method DataField\TimeRange      timeRange($start, $end, $label = '')
 * @method DataField\Number         number($column, $label = '')
 * @method DataField\Currency       currency($column, $label = '')
 * @method DataField\HasMany        hasMany($relationName, $callback)
 * @method DataField\SwitchField    switch($column, $label = '')
 * @method DataField\Display        display($column, $label = '')
 * @method DataField\Rate           rate($column, $label = '')
 * @method DataField\Divide         divide()
 * @method DataField\Password       password($column, $label = '')
 * @method DataField\Decimal        decimal($column, $label = '')
 * @method DataField\Html           html($html)
 * @method DataField\Tags           tags($column, $label = '')
 * @method DataField\Icon           icon($column, $label = '')
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
     * Ignored saving fields.
     *
     * @var array
     */
    protected $ignored = [];



    /**
     * Relation remove flag
     */
    const REMOVE_FLAG_NAME = '_remove_';

    /**
     * Create a new form instance.
     *
     * @param $model
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
        $field->setOwner($this);

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

        $this->fill($this->getModelData($id));

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

        $this->fill($this->getModelData($id));

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
            return $field instanceof DataField\File;
        })->each(function (File $file) use ($data) {
            $file->setOriginal($data);

            $file->destroy();
        });
    }

    /**
     * Store a new record.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = Input::all();

//        $this->prepareForTabs();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
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

            $this->updateRelation($this->relations);
        });

        if (($result = $this->complete($this->saved)) instanceof Response) {
            return $result;
        }

        if ($response = $this->ajaxSuccess(trans('admin::lang.save_succeeded'))) {
            return $response;
        }

        return $this->redirectAfterStore();
    }

    /**
     * Get RedirectResponse after store.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterStore()
    {
        $success = new MessageBag([
            'title'   => trans('admin::lang.succeeded'),
            'message' => trans('admin::lang.save_succeeded'),
        ]);

        $url = Input::get(Builder::PREVIOUS_URL_KEY) ?: $this->resource(0);

        return redirect($url)->with(compact('success'));
    }

    /**
     * Get ajax response.
     *
     * @param string $message
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    protected function ajaxSuccess($message)
    {
        $request = Request::capture();

        // ajax but not pjax
        if ($request->ajax() && !$request->pjax()) {
            return response()->json([
                'status'  => true,
                'message' => $message,
            ]);
        }

        return false;
    }

    /**
     * Get ajax error.
     *
     * @param string $message
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    protected function ajaxError($message)
    {
        $request = Request::capture();

        // ajax but not pjax
        if ($request->ajax() && !$request->pjax()) {
            return response()->json([
                'status'  => false,
                'message' => $message,
            ]);
        }

        return false;
    }

    /**
     * Prepare input data for insert or update.
     *
     * @param array    $data
     * @param callable $callback
     */
    protected function prepare($data = [], Closure $callback = null)
    {
        $this->inputs = $this->removeIgnoredFields($data);

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
     * Remove ignored fields from input.
     *
     * @param array $input
     *
     * @return array
     */
    protected function removeIgnoredFields($input)
    {
        array_forget($input, $this->ignored);

        return $input;
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
     * @return mixed|null
     */
    protected function complete(Closure $callback = null)
    {
        if ($callback instanceof Closure) {
            return $callback($this);
        }
    }


//    protected function prepareForTabs()
//    {
//        if ($tab = $this->builder->getTab()) {
//            $tab->getTabs()->each(function ($tab) {
//                $form = new static($this->model(), $tab['content']);
//
//                $this->builder->mergeFields($form->builder()->fields());
//            });
//        }
//    }

    /**
     * Handle update.
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id)
    {
        $data = Input::all();

//        $this->prepareForTabs();

        $data = $this->handleEditable($data);

        if ($this->handleOrderable($id, $data)) {
            return response([
                'status'  => true,
                'message' => trans('admin::lang.update_succeeded'),
            ]);
        }

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {

            if( $ajaxError = $this->ajaxError($validationMessages)){
                return $ajaxError;
            }

            return back()->withInput()->withErrors($validationMessages);
        }

        $this->model = $this->model->with($this->getRelations())->findOrNew($id);

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

        if (($result = $this->complete($this->saved)) instanceof Response) {
            return $result;
        }

        if ($response = $this->ajaxSuccess(trans('admin::lang.update_succeeded'))) {
            return $response;
        }

        return $this->redirectAfterUpdate();
    }




    /**
     * Get RedirectResponse after update.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterUpdate()
    {
        $success = new MessageBag([
            'title'   => trans('admin::lang.succeeded'),
            'message' => trans('admin::lang.update_succeeded'),
        ]);

        $url = Input::get(Builder::PREVIOUS_URL_KEY) ?: $this->resource(-1);

        return redirect($url)->with(compact('success'));
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
     * @return bool
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

            $relation = $this->model->$name();

            $hasDot = $relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne;

            $prepared = $this->prepareUpdate([$name => $values], $hasDot);

            if (empty($values)) {
                continue;
            }

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
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $related->setAttribute($column, $value);
                    }

                    $related->save();
                    break;
                case \Illuminate\Database\Eloquent\Relations\HasMany::class:

                    foreach($prepared as $ralated){

                        $relationModel = $this->model()->$name();

                        $keyName = $relationModel->getRelated()->getKeyName();

                        $instance = $relationModel->findOrNew($ralated[$keyName]);

                        if( $ralated[ static::REMOVE_FLAG_NAME ] == 1){

                            $instance->delete();

                            continue;
                        }

                        array_forget($ralated, static::REMOVE_FLAG_NAME);

                        $instance->fill($ralated);

                        $instance->save();
                    }

                    break;
            }
        }
    }




    /**
     * Prepare input data for update.
     *
     * @param array $updates
     * @param bool  $hasDot    If column name contains a 'dot', only has-one relation column use this.
     * @return array
     */
    protected function prepareUpdate(array $updates, $hasDot = false)
    {
        $prepared = [];

        foreach ($this->builder->fields() as $field) {
            $columns = $field->column();

            if (!$hasDot && Str::contains($columns, '.')) {
                continue;
            } elseif ($hasDot && !Str::contains($columns, '.')) {
                continue;
            }

            $value = $this->getDataByColumn($updates, $columns);

            if ($value !== '' && $value !== '0' && !$field instanceof File && empty($value)) {
                continue;
            }

            if (method_exists($field, 'prepare')) {
                $value = $field->prepare($value);
            }

            if( $field instanceof \Encore\Admin\Field\RelationField){
                foreach($field->fields() as $fld){
                    $prepared = $this->getPrepareInputs($prepared, $fld, $value);
                }
            }else{
                $prepared = $this->getPrepareInputs($prepared, $field, $value);
            }
        }

        return $prepared;
    }

    protected function getPrepareInputs($inputs, $field, $value)
    {
        if ($value != $field->original()) {
            $columns = $field->column();
            if (is_array($columns)) {
                foreach ($columns as $name => $column) {
                    array_set($inputs, $column, $value[$name]);
                }
            } elseif (is_string($columns)) {
                array_set($inputs, $columns, $value);
            }
        }
        return $inputs;
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
        if ($this->isHasOneRelation($inserts)) {
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
     * Is input data is has-one relation.
     *
     * @param array $inserts
     *
     * @return bool
     */
    protected function isHasOneRelation($inserts)
    {
        $first = current($inserts);

        if (!is_array($first)) {
            return false;
        }

        if (is_array(current($first))) {
            return false;
        }

        return Arr::isAssoc($first);
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
     * Ignore fields to save.
     *
     * @param string|array $fields
     *
     * @return $this
     */
    public function ignore($fields)
    {
        $this->ignored = (array) $fields;

        return $this;
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
            function (Field $field) use ($column) {
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
     * Get model data
     *
     * @param $id
     * @return mixed
     * author Edwin Hui
     */
    protected function getModelData($id)
    {
        $relations = $this->getRelations();

        $this->model = $this->model->with($relations)->findOrFail($id);

        return $this->model->toArray();
    }


    /**
     * Set all fields value in form.
     *
     * @param array $data
     * @return $this
     * author Edwin Hui
     */
    public function fill(array $data)
    {
        $this->builder->fields()->each(function (Field $field) use ($data) {

            $field->fill($data);
        });

        return $this;
    }

    /**
     * Get validation messages.
     *
     * @param array $input
     *
     * @return MessageBag|bool
     */
    protected function validationMessages($input)
    {
        $failedValidators = [];

        foreach ($this->builder->fields() as $field) {
            if (!$validator = $field->getValidator($input)) {
                continue;
            }

            if( $field instanceof \Encore\Admin\Field\RelationField){
                foreach($validator as $valid){
                    if (($valid instanceof Validator) && !$valid->passes()) {
                        $failedValidators[] = $valid;
                    }
                }
            }else {
                if (($validator instanceof Validator) && !$validator->passes()) {
                    $failedValidators[] = $validator;
                }
            }
        }

        $message = $this->mergeValidationMessages($failedValidators);

        return $message->any() ? $message : false;
    }



    /**
     * Merge validation messages from input validators.
     *
     * @param \Illuminate\Validation\Validator[] $validators
     *
     * @return MessageBag
     */
    protected function mergeValidationMessages($validators)
    {
        $messageBag = new MessageBag();

        foreach ($validators as $validator) {
            $messageBag = $messageBag->merge($validator->messages());
        }

        return $messageBag;
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



//    /**
//     * Use tab to split form.
//     *
//     * @param string $title
//     * @param Closure $content
//     *
//     * @return Tab
//     */
//    public function tab($title, Closure $content)
//    {
//        $tab = new Tab($this);
//
//        $this->builder->setTab($tab);
//
//        return $tab->tab($title, $content);
//    }
//
//    /**
//     * Use group to split form.
//     *
//     * @param string $title
//     * @param Closure $content
//     *
//     * @return Tab
//     */
//    public function group($title, Closure $content)
//    {
//        $group = new Group($this);
//
//        $this->builder->setGroup($group);
//
//        return $group->group($title, $content);
//    }

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
        if ($className = Field::findFieldClass($method)) {
            $column = array_get($arguments, 0, ''); //[0];

            $element = new $className( $column, array_slice($arguments, 1));

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
