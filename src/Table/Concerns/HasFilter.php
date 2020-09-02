<?php

namespace Encore\Admin\Table\Concerns;

use Closure;
use Encore\Admin\Table\Filter;
use Illuminate\Support\Collection;

trait HasFilter
{
    /**
     * The table Filter.
     *
     * @var \Encore\Admin\Table\Filter
     */
    protected $filter;

    /**
     * Setup table filter.
     *
     * @return $this
     */
    protected function initFilter()
    {
        $this->filter = new Filter($this->model());

        return $this;
    }

    /**
     * Disable table filter.
     *
     * @return $this
     */
    public function disableFilter(bool $disable = true)
    {
        $this->tools->disableFilterButton($disable);

        return $this->option('show_filter', !$disable);
    }

    /**
     * Get filter of Table.
     *
     * @return Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Process the table filter.
     *
     * @param bool $toArray
     *
     * @return array|Collection|mixed
     */
    public function applyFilter($toArray = true)
    {
        return $this->filter->execute($toArray);
    }

    /**
     * Set the table filter.
     *
     * @param Closure $callback
     */
    public function filter(Closure $callback)
    {
        call_user_func($callback, $this->filter);
    }

    /**
     * Render the table filter.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function renderFilter()
    {
        if (!$this->option('show_filter')) {
            return '';
        }

        return $this->filter->render();
    }

    /**
     * Expand filter.
     *
     * @return $this
     */
    public function expandFilter()
    {
        $this->filter->expand();

        return $this;
    }
}
