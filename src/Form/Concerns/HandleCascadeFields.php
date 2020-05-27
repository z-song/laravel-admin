<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field;

trait HandleCascadeFields
{
    /**
     * @param array $dependency
     * @param \Closure $closure
     */
    public function cascadeGroup(\Closure $closure, array $dependency)
    {
        $this->pushField($group = new Field\CascadeGroup($dependency));

        call_user_func($closure, $this);

        $group->end();
    }
}
