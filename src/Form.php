<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Exception\Handle;
use Encore\Admin\Form\Builder;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\Field\File;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Form\Tab;
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
 * @method Field\DateTimeRange  datetimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Number         number($column, $label = '')
 * @method Field\Currency       currency($column, $label = '')
 * @method Field\HasMany        hasMany($relationName, $callback)
 * @method Field\SwitchField    switch($column, $label = '')
 * @method Field\Display        display($column, $label = '')
 * @method Field\Rate           rate($column, $label = '')
 * @method Field\Divide         divide()
 * @method Field\Password       password($column, $label = '')
 * @method Field\Decimal        decimal($column, $label = '')
 * @method Field\Html           html($html, $label = '')
 * @method Field\Tags           tags($column, $label = '')
 * @method Field\Icon           icon($column, $label = '')
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
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [];

    /**
     * Ignored saving fields.
     *
     * @var array
     */
    protected $ignored = [];

    /**
     * Collected field assets.
     *
     * @var array
     */
    protected static $collectedAssets = [];

    /**
     * @var Form\Tab
     */
    protected $tab = null;

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
     * Use tab to split form.
     *
     * @param string  $title
     * @param Closure $content
     *
     * @return $this
     */
    public function tab($title, Closure $content, $active = false)
    {
        $this->getTab()->append($title, $content, $active);

        return $this;
    }

    /**
     * Get Tab instance.
     *
     * @return Tab
     */
    public function getTab()
    {
        if (is_null($this->tab)) {
            $this->tab = new Tab($this);
        }

        return $this->tab;
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = Input::all();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

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

        if (($response = $this->complete($this->saved)) instanceof Response) {
            return $response;
        }

        if ($response = $this->ajaxResponse(trans('admin::lang.save_succeeded'))) {
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
    protected function ajaxResponse($message)
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
     * Prepare input data for insert or update.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected function prepare($data = [])
    {
        $this->inputs = $this->removeIgnoredFields($data);

        if (($response = $this->callSaving()) instanceof Response) {
            return $response;
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
     * Call saving callback.
     *
     * @return mixed
     */
    protected function callSaving()
    {
        if ($this->saving instanceof Closure) {
            return call_user_func($this->saving, $this);
        }
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
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }

                        $related->setAttribute($column, $value);
                    }

                    $relation->save($related);
                    break;
                case \Illuminate\Database\Eloquent\Relations\HasMany::class:

                    $nestedForm = new NestedForm($relation);

                    $nestedForm->update($values[$name]);

                    break;
            }
        }
    }

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

        $data = $this->handleEditable($data);

        if ($this->handleOrderable($id, $data)) {
            return response([
                'status'  => true,
                'message' => trans('admin::lang.update_succeeded'),
            ]);
        }

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
        }

        $this->model = $this->model->with($this->getRelations())->findOrFail($id);

        $this->setFieldOriginalValue();

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

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

        if ($response = $this->ajaxResponse(trans('admin::lang.update_succeeded'))) {
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

            if (empty($prepared)) {
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

                    $nestedForm = new NestedForm($relation);

                    $nestedForm->update($prepared[$name]);

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

    /**
     * Prepare input data for update.
     *
     * @param array $updates
     * @param bool  $hasDot  If column name contains a 'dot', only has-one relation column use this.
     *
     * @return array
     */
    protected function prepareUpdate(array $updates, $hasDot = false)
    {
        $prepared = [];

        foreach ($this->builder->fields() as $field) {
            $columns = $field->column();

            if ($this->invalidColumn($columns, $hasDot)) {
                continue;
            }

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
     * @param string|array $columns
     * @param bool         $hasDot
     *
     * @return bool
     */
    public function invalidColumn($columns, $hasDot = false)
    {
        foreach ((array) $columns as $column) {
            if ((!$hasDot && Str::contains($column, '.')) ||
                ($hasDot && !Str::contains($column, '.'))) {
                return true;
            }
        }

        return false;
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
     * @param Closure $callback
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

            if (($validator instanceof Validator) && !$validator->passes()) {
                $failedValidators[] = $validator;
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
        $segments = explode('/', trim(app('request')->getUri(), '/'));

        if ($slice != 0) {
            $segments = array_slice($segments, 0, $slice);
        }

        return implode('/', $segments);
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
     * Register builtin fields.
     *
     * @return void
     */
    public static function registerBuiltinFields()
    {
        $map = [
            'button'            => \Encore\Admin\Form\Field\Button::class,
            'checkbox'          => \Encore\Admin\Form\Field\Checkbox::class,
            'color'             => \Encore\Admin\Form\Field\Color::class,
            'currency'          => \Encore\Admin\Form\Field\Currency::class,
            'date'              => \Encore\Admin\Form\Field\Date::class,
            'dateRange'         => \Encore\Admin\Form\Field\DateRange::class,
            'datetime'          => \Encore\Admin\Form\Field\Datetime::class,
            'dateTimeRange'     => \Encore\Admin\Form\Field\DatetimeRange::class,
            'datetimeRange'     => \Encore\Admin\Form\Field\DatetimeRange::class,
            'decimal'           => \Encore\Admin\Form\Field\Decimal::class,
            'display'           => \Encore\Admin\Form\Field\Display::class,
            'divider'           => \Encore\Admin\Form\Field\Divide::class,
            'divide'            => \Encore\Admin\Form\Field\Divide::class,
            'editor'            => \Encore\Admin\Form\Field\Editor::class,
            'email'             => \Encore\Admin\Form\Field\Email::class,
            'embedsMany'        => \Encore\Admin\Form\Field\EmbedsMany::class,
            'file'              => \Encore\Admin\Form\Field\File::class,
            'hasMany'           => \Encore\Admin\Form\Field\HasMany::class,
            'hidden'            => \Encore\Admin\Form\Field\Hidden::class,
            'id'                => \Encore\Admin\Form\Field\Id::class,
            'image'             => \Encore\Admin\Form\Field\Image::class,
            'ip'                => \Encore\Admin\Form\Field\Ip::class,
            'map'               => \Encore\Admin\Form\Field\Map::class,
            'mobile'            => \Encore\Admin\Form\Field\Mobile::class,
            'month'             => \Encore\Admin\Form\Field\Month::class,
            'multipleSelect'    => \Encore\Admin\Form\Field\MultipleSelect::class,
            'number'            => \Encore\Admin\Form\Field\Number::class,
            'password'          => \Encore\Admin\Form\Field\Password::class,
            'radio'             => \Encore\Admin\Form\Field\Radio::class,
            'rate'              => \Encore\Admin\Form\Field\Rate::class,
            'select'            => \Encore\Admin\Form\Field\Select::class,
            'slider'            => \Encore\Admin\Form\Field\Slider::class,
            'switch'            => \Encore\Admin\Form\Field\SwitchField::class,
            'text'              => \Encore\Admin\Form\Field\Text::class,
            'textarea'          => \Encore\Admin\Form\Field\Textarea::class,
            'time'              => \Encore\Admin\Form\Field\Time::class,
            'timeRange'         => \Encore\Admin\Form\Field\TimeRange::class,
            'url'               => \Encore\Admin\Form\Field\Url::class,
            'year'              => \Encore\Admin\Form\Field\Year::class,
            'html'              => \Encore\Admin\Form\Field\Html::class,
            'tags'              => \Encore\Admin\Form\Field\Tags::class,
            'icon'              => \Encore\Admin\Form\Field\Icon::class,
        ];

        foreach ($map as $abstract => $class) {
            static::extend($abstract, $class);
        }
    }

    /**
     * Register custom field.
     *
     * @param string $abstract
     * @param string $class
     *
     * @return void
     */
    public static function extend($abstract, $class)
    {
        static::$availableFields[$abstract] = $class;
    }

    /**
     * Remove registered field.
     *
     * @param array|string $abstract
     */
    public static function forget($abstract)
    {
        array_forget(static::$availableFields, $abstract);
    }

    /**
     * Find field class.
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    public static function findFieldClass($method)
    {
        $class = array_get(static::$availableFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * Collect assets required by registered field.
     *
     * @return array
     */
    public static function collectFieldAssets()
    {
        if (!empty(static::$collectedAssets)) {
            return static::$collectedAssets;
        }

        $css = collect();
        $js = collect();

        foreach (static::$availableFields as $field) {
            if (!method_exists($field, 'getAssets')) {
                continue;
            }

            $assets = call_user_func([$field, 'getAssets']);

            $css->push(array_get($assets, 'css'));
            $js->push(array_get($assets, 'js'));
        }

        return static::$collectedAssets = [
            'css' => $css->flatten()->unique()->filter()->toArray(),
            'js'  => $js->flatten()->unique()->filter()->toArray(),
        ];
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
