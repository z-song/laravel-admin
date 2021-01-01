<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Builder;
use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;

trait HasResponse
{
    /**
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function quickCreateResponse()
    {
        if (request()->has('__quick_create')) {
            return response([
                'status'    => true,
                'message'   => trans('admin.save_succeeded'),
            ]);
        }

        return false;
    }

    /**
     * @return array
     */
    protected function applayFieldDisplay()
    {
        $editable = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            if (!request()->has($field->column())) {
                continue;
            }

            $newValue = $this->model->fresh()->getAttribute($field->column());

            if ($newValue instanceof Arrayable) {
                $newValue = $newValue->toArray();
            }

            if ($field instanceof Field\BelongsTo || $field instanceof Field\BelongsToMany) {
                $selectable = $field->getSelectable();

                if (method_exists($selectable, 'display')) {
                    $display = $selectable::display();

                    $editable[$field->column()] = $display->call($this->model, $newValue);
                }
            }
        }

        return $editable;
    }

    /**
     * Get inline edit response.
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    protected function inlineEditResponse()
    {
        // ajax but not pjax
        if (request('__inline_edit')) {
            return response([
                'status'    => true,
                'message'   => trans('admin.update_succeeded'),
                'display'   => $this->applayFieldDisplay(),
            ]);
        }

        return false;
    }

    /**
     * Get RedirectResponse after store.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterStore()
    {
        $resourcesPath = $this->resource(0);

        $key = $this->model->getKey();

        return $this->redirectAfterSaving($resourcesPath, $key);
    }

    /**
     * Get RedirectResponse after update.
     *
     * @param mixed $key
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterUpdate($key)
    {
        $resourcesPath = $this->resource(-1);

        return $this->redirectAfterSaving($resourcesPath, $key);
    }

    /**
     * Get RedirectResponse after data saving.
     *
     * @param string $resourcesPath
     * @param string $key
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function redirectAfterSaving($resourcesPath, $key)
    {
        $response = [
            'status'  => true,
            'message' => trans('admin.save_succeeded'),
        ];

        if (request('_saved') == 1) {
            // continue editing
            $response['refresh'] = true;
        } elseif (request('_saved') == 2) {
            // continue creating
            $response['redirect'] = rtrim($resourcesPath, '/').'/create';
        } elseif (request('_saved') == 3) {
            // view resource
            $response['redirect'] = rtrim($resourcesPath, '/')."/{$key}";
        } else {
            $response['redirect'] = request(Builder::PREVIOUS_URL_KEY) ?: $resourcesPath;
        }

        return \response()->json($response);
    }
}
