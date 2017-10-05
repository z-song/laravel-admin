<?php

namespace Encore\Admin\Controllers;

trait ModelForm
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->edit($id);
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->form()->destroy($id)) {
            $response = [
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $response = [
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        if (request()->ajax())
        {
            $return = response()->json($response);
        }
        else
        {
            admin_toastr($response['message'], $response['status']?'success':'error');
            $return = redirect()->back();
        }

        return $return;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->form()->store();
    }
}
