<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Exception\Handler;
use Encore\Admin\Form\Builder;
use Encore\Admin\Form\Concerns;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\Footer;
use Encore\Admin\Form\Layout\Layout;
use Encore\Admin\Form\Row;
use Encore\Admin\Form\Tab;
use Encore\Admin\Traits\ShouldSnakeAttributes;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Form.
 */
class Form implements Renderable
{
    use Concerns\HasHooks;
    use Concerns\HasFields;
    use Concerns\HasResponse;
    use Concerns\ValidatesFields;
    use Concerns\HandleCascadeFields;
    use ShouldSnakeAttributes;
    /**
     * Remove flag in `has many` form.
     */
    const REMOVE_FLAG_NAME = '_remove_';

    /**
     * Eloquent model of the form.
     *
     * @var Model
     */
    protected $model;

    /**
     * @var Builder
     */
    protected $builder;

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
     * @var Layout
     */
    protected $layout;

    /**
     * Ignored saving fields.
     *
     * @var array
     */
    protected $ignored = [];

    /**
     * @var Form\Tab
     */
    protected $tab = null;

    /**
     * Field rows in form.
     *
     * @var array
     */
    public $rows = [];

    /**
     * @var null
     */
    public $currentRow = null;

    /**
     * @var bool
     */
    protected $isSoftDeletes = false;

    /**
     * Create a new form instance.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;

        $this->builder = new Builder($this);

        $this->initLayout();

        $this->isSoftDeletes = in_array(SoftDeletes::class, class_uses_deep($this->model), true);

        $this->callInitCallbacks();
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field): self
    {
        $field->setForm($this);

        $width = $this->builder->getWidth();
        $field->setWidth($width['field'], $width['label']);

        $this->fields()->push($field);
        $this->layout->addField($field);

        return $this;
    }

    /**
     * @return Model
     */
    public function model(): Model
    {
        return $this->model;
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return $this->builder;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fields()
    {
        return $this->builder()->fields();
    }

    /**
     * Generate a edit form.
     *
     * @param $id
     *
     * @return $this
     */
    public function edit($id): self
    {
        $this->builder->setMode(Builder::MODE_EDIT);
        $this->builder->setResourceId($id);

        $this->setFieldValue($id);

        return $this;
    }

    /**
     * Generate a create form.
     *
     * @return $this
     */
    public function create(): self
    {
        $this->callCreating();

        return $this;
    }

    /**
     * Use tab to split form.
     *
     * @param string  $title
     * @param Closure $content
     * @param bool    $active
     *
     * @return $this
     */
    public function tab($title, Closure $content, bool $active = false): self
    {
        $this->setTab()->append($title, $content, $active);

        return $this;
    }

    /**
     * Get Tab instance.
     *
     * @return Tab
     */
    public function getTab()
    {
        return $this->tab;
    }

    /**
     * Set Tab instance.
     *
     * @return Tab
     */
    public function setTab(): Tab
    {
        if ($this->tab === null) {
            $this->tab = new Tab($this);
        }

        return $this->tab;
    }

    /**
     * Store a new record.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = request()->all();

        // Handle validation errors.
        if ($response = $this->validateErrorResponse($data)) {
            return $response;
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

        DB::transaction(function () {
            $inserts = $this->prepareInsert($this->updates);

            foreach ($inserts as $column => $value) {
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->updateRelation($this->relations);
        });

        if (($response = $this->callSaved()) instanceof Response) {
            return $response;
        }

        // For quick create store
        if ($response = $this->quickCreateResponse()) {
            return $response;
        }

        return $this->redirectAfterStore();
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
        if (($response = $this->callSubmitted()) instanceof Response) {
            return $response;
        }

        $this->inputs = array_merge($this->removeIgnoredFields($data), $this->inputs);

        if (($response = $this->callSaving()) instanceof Response) {
            return $response;
        }

        $this->relations = $this->getRelationInputs($this->inputs);

        $this->updates = Arr::except($this->inputs, array_keys($this->relations));
    }

    /**
     * Remove ignored fields from input.
     *
     * @param array $input
     *
     * @return array
     */
    protected function removeIgnoredFields($input): array
    {
        Arr::forget($input, $this->ignored);

        return $input;
    }

    /**
     * Get inputs for relations.
     *
     * @param array $inputs
     *
     * @return array
     */
    protected function getRelationInputs($inputs = []): array
    {
        $relations = [];

        foreach ($inputs as $column => $value) {
            if (in_array($column, ['_token', '_saved', '_method'])) {
                continue;
            }

            if (method_exists($this->model, $column) ||
                method_exists($this->model, $column = Str::camel($column))) {
                $relation = call_user_func([$this->model, $column]);

                if ($relation instanceof Relations\Relation) {
                    $relations[$column] = $value;
                }
            }
        }

        return $relations;
    }

    /**
     * Handle update.
     *
     * @param int  $id
     * @param null $data
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|mixed|null|Response
     */
    public function update($id, $data = null)
    {
        $data = ($data) ?: request()->all();

        $this->callEditing();

        if (($data = $this->handleColumnUpdates($id, $data)) instanceof Response) {
            return $data;
        }

        /* @var Model $this ->model */
        $builder = $this->model();

        if ($this->isSoftDeletes) {
            $builder = $builder->withTrashed();
        }

        $this->model = $builder->with($this->getRelations())->findOrFail($id);

        $this->setFieldOriginalValue();

        // Handle validation errors.
        if ($response = $this->validateErrorResponse($data)) {
            return $response;
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

        DB::transaction(function () {
            $updates = $this->prepareUpdate($this->updates);

            foreach ($updates as $column => $value) {
                /* @var Model $this ->model */
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->updateRelation($this->relations);
        });

        if (($result = $this->callSaved()) instanceof Response) {
            return $result;
        }

        // For inline edit updates.
        if ($response = $this->inlineEditResponse()) {
            return $response;
        }

        return $this->redirectAfterUpdate($id);
    }

    /**
     * Handle updates for single column.
     *
     * @param int   $id
     * @param array $data
     *
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|Response
     */
    protected function handleColumnUpdates($id, $data)
    {
        $data = $this->handleFileDelete($data);

        $data = $this->handleFileSort($data);

        return $data;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function handleFileDelete(array $input = []): array
    {
        if (array_key_exists(Field::FILE_DELETE_FLAG, $input)) {
            $input[Field::FILE_DELETE_FLAG] = $input['key'];
            unset($input['key']);
        }

        request()->replace($input);

        return $input;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function handleFileSort(array $input = []): array
    {
        if (!array_key_exists(Field::FILE_SORT_FLAG, $input)) {
            return $input;
        }

        $sorts = array_filter($input[Field::FILE_SORT_FLAG]);

        if (empty($sorts)) {
            return $input;
        }

        foreach ($sorts as $column => $order) {
            $input[$column] = $order;
        }

        request()->replace($input);

        return $input;
    }

    /**
     * Update relation data.
     *
     * @param array $relationsData
     *
     * @return void
     */
    protected function updateRelation($relationsData)
    {
        foreach ($relationsData as $name => $values) {
            if (!method_exists($this->model, $name)) {
                continue;
            }

            $relation = $this->model->$name();

            $oneToOneRelation = $relation instanceof Relations\HasOne
                || $relation instanceof Relations\MorphOne
                || $relation instanceof Relations\BelongsTo;

            $prepared = $this->prepareUpdate([$name => $values], $oneToOneRelation);

            if (empty($prepared)) {
                continue;
            }

            switch (true) {
                case $relation instanceof Relations\BelongsToMany:
                case $relation instanceof Relations\MorphToMany:
                    if (isset($prepared[$name])) {
                        $relation->sync($prepared[$name]);
                    }
                    break;
                case $relation instanceof Relations\HasOne:
                case $relation instanceof Relations\MorphOne:
                    $related = $this->model->getRelationValue($name) ?: $relation->getRelated();

                    foreach ($prepared[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }

                    // save child
                    $relation->save($related);
                    break;
                case $relation instanceof Relations\BelongsTo:
                case $relation instanceof Relations\MorphTo:
                    $related = $this->model->getRelationValue($name) ?: $relation->getRelated();

                    foreach ($prepared[$name] as $column => $value) {
                        $related->setAttribute($column, $value);
                    }

                    // save parent
                    $related->save();

                    // save child (self)
                    $relation->associate($related)->save();
                    break;
                case $relation instanceof Relations\HasMany:
                case $relation instanceof Relations\MorphMany:
                    foreach ($prepared[$name] as $related) {
                        /** @var Relations\HasOneOrMany $relation */
                        $relation = $this->model->$name();

                        $keyName = $relation->getRelated()->getKeyName();

                        /** @var Model $child */
                        $child = $relation->findOrNew(Arr::get($related, $keyName));

                        if (Arr::get($related, static::REMOVE_FLAG_NAME) == 1) {
                            $child->delete();
                            continue;
                        }

                        Arr::forget($related, static::REMOVE_FLAG_NAME);

                        $child->fill($related);

                        $child->save();
                    }
                    break;
            }
        }
    }

    /**
     * Prepare input data for update.
     *
     * @param array $updates
     * @param bool  $oneToOneRelation If column is one-to-one relation.
     *
     * @return array
     */
    protected function prepareUpdate(array $updates, $oneToOneRelation = false): array
    {
        $prepared = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            $columns = $field->column();

            // If column not in input array data, then continue.
            if (!Arr::has($updates, $columns)) {
                continue;
            }

            if ($this->isInvalidColumn($columns, $oneToOneRelation || $field->isJsonType)) {
                continue;
            }

            $value = $this->getDataByColumn($updates, $columns);

            $value = $field->prepare($value);

            if (is_array($columns)) {
                foreach ($columns as $name => $column) {
                    Arr::set($prepared, $column, $value[$name]);
                }
            } elseif (is_string($columns)) {
                Arr::set($prepared, $columns, $value);
            }
        }

        return $prepared;
    }

    /**
     * @param string|array $columns
     * @param bool         $containsDot
     *
     * @return bool
     */
    protected function isInvalidColumn($columns, $containsDot = false): bool
    {
        foreach ((array) $columns as $column) {
            if ((!$containsDot && Str::contains($column, '.')) ||
                ($containsDot && !Str::contains($column, '.'))) {
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
    protected function prepareInsert($inserts): array
    {
        if ($this->isHasOneRelation($inserts)) {
            $inserts = Arr::dot($inserts);
        }

        foreach ($inserts as $column => $value) {
            if (($field = $this->getFieldByColumn($column)) === null) {
                unset($inserts[$column]);
                continue;
            }

            $inserts[$column] = $field->prepare($value);
        }

        $prepared = [];

        foreach ($inserts as $key => $value) {
            Arr::set($prepared, $key, $value);
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
    protected function isHasOneRelation($inserts): bool
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
     * Ignore fields to save.
     *
     * @param string|array $fields
     *
     * @return $this
     */
    public function ignore($fields): self
    {
        $this->ignored = array_merge($this->ignored, (array) $fields);

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
            return Arr::get($data, $columns);
        }

        if (is_array($columns)) {
            $value = [];
            foreach ($columns as $name => $column) {
                if (!Arr::has($data, $column)) {
                    continue;
                }
                $value[$name] = Arr::get($data, $column);
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
        return $this->fields()->first(
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

        $this->fields()->each(function (Field $field) use ($values) {
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

        $builder = $this->model();

        if ($this->isSoftDeletes) {
            $builder = $builder->withTrashed();
        }

        $this->model = $builder->with($relations)->findOrFail($id);

        $this->callEditing();

        $data = $this->model->toArray();

        $this->fields()->each(function (Field $field) use ($data) {
            if (!in_array($field->column(), $this->ignored, true)) {
                $field->fill($data);
            }
        });
    }

    /**
     * Add a fieldset to form.
     *
     * @param string  $title
     * @param Closure $setCallback
     *
     * @return Field\Fieldset
     */
    public function fieldset(string $title, Closure $setCallback)
    {
        $fieldset = new Field\Fieldset();

        $this->html($fieldset->start($title))->plain();

        $setCallback($this);

        $this->html($fieldset->end())->plain();

        return $fieldset;
    }

    /**
     * Get all relations of model from callable.
     *
     * @return array
     */
    public function getRelations(): array
    {
        $relations = $columns = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            $columns[] = $field->column();
        }

        foreach (Arr::flatten($columns) as $column) {
            if (Str::contains($column, '.')) {
                list($relation) = explode('.', $column);

                if (method_exists($this->model, $relation) &&
                    $this->model->$relation() instanceof Relations\Relation
                ) {
                    $relations[] = $relation;
                }
            } elseif (method_exists($this->model, $column) &&
                !method_exists(Model::class, $column)
            ) {
                $relations[] = $column;
            }
        }

        return array_unique($relations);
    }

    /**
     * Set action for form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action): self
    {
        $this->builder()->setAction($action);

        return $this;
    }

    /**
     * Set field and label width in current form.
     *
     * @param int $fieldWidth
     * @param int $labelWidth
     *
     * @return $this
     */
    public function setWidth($fieldWidth = 8, $labelWidth = 2): self
    {
        $this->fields()->each(function ($field) use ($fieldWidth, $labelWidth) {
            /* @var Field $field  */
            $field->setWidth($fieldWidth, $labelWidth);
        });

        $this->builder()->setWidth($fieldWidth, $labelWidth);

        return $this;
    }

    /**
     * Set view for form.
     *
     * @param string $view
     *
     * @return $this
     */
    public function setView($view): self
    {
        $this->builder()->setView($view);

        return $this;
    }

    /**
     * Set title for form.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title = ''): self
    {
        $this->builder()->setTitle($title);

        return $this;
    }

    /**
     * Set a submit confirm.
     *
     * @param string $message
     * @param string $on
     *
     * @return $this
     */
    public function confirm(string $message, $on = null)
    {
        if ($on && !in_array($on, ['create', 'edit'])) {
            throw new \InvalidArgumentException("The second paramater `\$on` must be one of ['create', 'edit']");
        }

        if ($on == 'create' && !$this->isCreating()) {
            return;
        }

        if ($on == 'edit' && !$this->isEditing()) {
            return;
        }

        $this->builder()->confirm($message);

        return $this;
    }

    /**
     * Add a row in form.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function row(Closure $callback = null)
    {
        $this->rows[] = $this->currentRow = new Row($callback, $this);

        if (is_null($callback)) {
            return $this->currentRow;
        }

        return $this;
    }

    /**
     * Tools setting for form.
     *
     * @param Closure $callback
     */
    public function tools(Closure $callback)
    {
        $callback->call($this, $this->builder->getTools());
    }

    /**
     * Disable form tools.
     *
     * @return $this
     */
    public function disableTools()
    {
        $this->builder->getTools()->disable();

        return $this;
    }

    /**
     * @param Closure|null $callback
     *
     * @return Form\Tools
     */
    public function header(Closure $callback = null)
    {
        if (func_num_args() === 0) {
            return $this->builder->getTools();
        }

        $callback->call($this, $this->builder->getTools());
    }

    /**
     * Indicates if current form page is creating.
     *
     * @return bool
     */
    public function isCreating(): bool
    {
        return Str::endsWith(request()->route()->getName(), ['.create', '.store']);
    }

    /**
     * Indicates if current form page is editing.
     *
     * @return bool
     */
    public function isEditing(): bool
    {
        return Str::endsWith(request()->route()->getName(), ['.edit', '.update']);
    }

    /**
     * @return mixed
     */
    public function getResourceId()
    {
        return current(request()->route()->parameters());
    }

    /**
     * Disable View Checkbox on footer.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableViewCheck(bool $disable = true): self
    {
        $this->builder()->getFooter()->disableViewCheck($disable);

        return $this;
    }

    /**
     * Disable Editing Checkbox on footer.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableEditingCheck(bool $disable = true): self
    {
        $this->builder()->getFooter()->disableEditingCheck($disable);

        return $this;
    }

    /**
     * Disable Creating Checkbox on footer.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableCreatingCheck(bool $disable = true): self
    {
        $this->builder()->getFooter()->disableCreatingCheck($disable);

        return $this;
    }

    /**
     * Disable all footer checkbox.
     *
     * @return $this
     */
    public function disableFooterCheck()
    {
        $this->footer(function (Footer $footer) {
            $footer->disableViewCheck()
                ->disableEditingCheck()
                ->disableCreatingCheck();
        });

        return $this;
    }

    /**
     * Footer setting for form.
     *
     * @param Closure $callback
     *
     * @return \Encore\Admin\Form\Footer
     */
    public function footer(Closure $callback = null)
    {
        if (func_num_args() === 0) {
            return $this->builder()->getFooter();
        }

        $callback($this->builder()->getFooter());
    }

    /**
     * Get current resource route url.
     *
     * @param int $slice
     *
     * @return string
     */
    public function resource($slice = -2): string
    {
        $segments = explode('/', trim(request()->getUri(), '/'));

        if ($slice !== 0) {
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
            return Handler::renderException($e);
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
        if ($value === null) {
            return Arr::get($this->inputs, $key);
        }

        return Arr::set($this->inputs, $key, $value);
    }

    /**
     * Add a new layout column.
     *
     * @param int      $width
     * @param \Closure $closure
     *
     * @return $this
     */
    public function column($width, \Closure $closure): self
    {
        $width = $width < 1 ? round(12 * $width) : $width;

        $this->layout->column($width, $closure);

        return $this;
    }

    /**
     * Initialize filter layout.
     */
    protected function initLayout()
    {
        $this->layout = new Layout($this);
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
     * @param mixed  $value
     *
     * @return array
     */
    public function __set($name, $value)
    {
        return Arr::set($this->inputs, $name, $value);
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field
     */
    public function __call($method, $arguments)
    {
        if ($className = static::findFieldClass($method)) {
            $column = Arr::get($arguments, 0, ''); //[0];

            $element = new $className($column, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }

        admin_error('Error', "Field type [$method] does not exist.");

        return new Field\Nullable();
    }

    /**
     * @return Layout
     */
    public function getLayout(): Layout
    {
        return $this->layout;
    }
}
