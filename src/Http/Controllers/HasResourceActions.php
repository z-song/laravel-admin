<?php

namespace Encore\Admin\Http\Controllers;

use Encore\Admin\Actions\Response;

trait HasResourceActions
{
    protected $response;

    /**
     * @param string $plugin 'swal' or 'toastr'
     * @return Response
     */
    public function response($plugin = 'swal')
    {
        if (is_null($this->response)) {
            $this->response = new Response();
        }

        $this->response->$plugin();

        return $this->response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->form()->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->create()->store();
    }

    /**
     * Destroy record.
     *
     * @param $id
     */
    public function destroy($id)
    {
        return app(HandleController::class)->handleAction(request());
    }
}
