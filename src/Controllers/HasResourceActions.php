<?php

namespace Encore\Admin\Controllers;

trait HasResourceActions
{
    /**
     * Route parameter name to use for edition or deletion
     * Forexample in the `route/{id}/edit` the `id` is route-parameter-name
     * Useful for when we have multiple parameter name like `order/{order_id}/payment/{payment_id}/edit` and we want to detect object using `payment_id`
     */
    public $routeParamName = null;

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $id = $this->getIdFromRoute();
        return $this->form()->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->store();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = $this->getIdFromRoute();
        return $this->form()->destroy($id);
    }

    protected function getIdFromRoute()
    {
        $routeParameters = request()->route()->parameters();
        $id = is_null($this->routeParamName) ? end($routeParameters) : request()->route($this->routeParamName);
        $this->editingId = $id;

        return $id;
    }
}
