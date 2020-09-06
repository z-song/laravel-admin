<?php

namespace Encore\Admin\Table\Concerns;

use Closure;
use Encore\Admin\Table\Tools;

trait HasTools
{
    use HasQuickSearch;

    /**
     * Header tools.
     *
     * @var Tools
     */
    public $tools;

    /**
     * Setup table tools.
     *
     * @return $this
     */
    protected function initTools()
    {
        $this->tools = new Tools($this);

        return $this;
    }

    /**
     * Disable header tools.
     *
     * @return $this
     */
    public function disableTools(bool $disable = true)
    {
        return $this->option('show_tools', !$disable);
    }

    /**
     * Setup table tools.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function tools(Closure $callback)
    {
        call_user_func($callback, $this->tools);
    }

    /**
     * Render custom tools.
     *
     * @return string
     */
    public function renderHeaderTools()
    {
        return $this->tools->render();
    }

    /**
     * If table show header tools.
     *
     * @return bool
     */
    public function showTools()
    {
        return $this->option('show_tools');
    }
}
