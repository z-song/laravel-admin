<?php

namespace Encore\Admin\Traits;

trait Resource
{
    /**
     * get resource to grid.
     */
    protected function getResource($slice)
    {
        // create uri
        $segments = [];

        // set url
        foreach (explode('/', trim(app('request')->getPathInfo(), '/')) as $value) {
            $segments[] = $value;
        }

        if ($slice != 0) {
            $segments = array_slice($segments, 0, $slice);
        }

        return '/'.implode('/', $segments);
    }
}
