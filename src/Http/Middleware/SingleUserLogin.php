<?php

namespace Encore\Admin\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\Middleware\AuthenticateSession;

class SingleUserLogin extends AuthenticateSession
{
    /**
     * {@inheritdoc}
     */
    protected function logout($request)
    {
        $this->auth->logoutCurrentDevice();

        $request->session()->flush();

        throw new AuthenticationException('Unauthenticated.', [], route(config('admin.route.as') . 'login'));
    }
}
