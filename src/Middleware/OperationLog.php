<?php

namespace Encore\Admin\Middleware;

use Carbon\Carbon;
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

            // log if request method is not excluded
            $method = $request->method();
            $excludedMethods = (array)config('admin.operation_log_settings.exclude_methods');
            if (!in_array($method, $excludedMethods)) {
                $log = [
                    'user_id' => Admin::user()->id,
                    'path' => $request->path(),
                    'method' => $method,
                    'ip' => $request->getClientIp(),
                    'input' => json_encode($request->input()),
                ];

                \Encore\Admin\Auth\Database\OperationLog::create($log);
            }

            // clear logs once a day if max age is defined
            $maxDays = (int)config('admin.operation_log_settings.max_age_in_days');
            $cacheKey = 'LA_logs_cleared';
            if ($maxDays > 0 && !\Cache::has($cacheKey)) {
                \Encore\Admin\Auth\Database\OperationLog::
                whereDate('created_at', '>', Carbon::now()->addDays($maxDays)->toDateString())
                    ->delete();
                \Cache::put($cacheKey, true, 60 * 24);
            }
        }

        return $next($request);
    }
}
