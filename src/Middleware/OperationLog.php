<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class OperationLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (config('admin.operation_log') && Admin::user()) {
            $log = [
                'user_id' => Admin::user()->id,
                'path'    => $request->path(),
                'method'  => $request->method(),
                'ip'      => $request->getClientIp(),
                'input'   => $this->hidePasswords(json_encode($request->input())),
            ];

            \Encore\Admin\Auth\Database\OperationLog::create($log);
        }

        return $next($request);
    }

    /**
     * Replace passwords with stars in operation log.
     * @see https://github.com/z-song/laravel-admin/issues/625
     *
     * @param string $stringToLog
     *
     * @return string
     */
    public function hidePasswords($stringToLog)
    {
        return preg_replace('#("(password|_token|password_confirmation)"\s*:\s*")([^"]*)"#', '\1***"', $stringToLog);
    }
}
