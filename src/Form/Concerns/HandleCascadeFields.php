<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field;

trait HandleCascadeFields
{
    /**
     * @var array
     */
    protected $dependency;

    /**
     * @return array
     */
    public function getDependency()
    {
        return $this->dependency;
    }

    /**
     * @param array $dependency
     * @param \Closure $closure
     */
    public function callWithDependency(array $dependency, \Closure $closure)
    {
        $this->dependency = $dependency;

        call_user_func($closure, $this);

        $this->dependency = null;
    }
}
