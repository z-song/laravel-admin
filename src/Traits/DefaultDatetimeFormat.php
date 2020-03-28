<?php

namespace Encore\Admin\Traits;

use Carbon\Carbon;

trait DefaultDatetimeFormat
{
    protected function serializeDate(\DateTimeInterface $date)
    {
        if (version_compare(app()->version(), '7.0.0') < 0) {
            return parent::serializeDate($date);
        }

        return $date->format(Carbon::DEFAULT_TO_STRING_FORMAT);
    }
}
