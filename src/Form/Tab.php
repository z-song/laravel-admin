<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;

class Tab
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var []TabItem
     */
    protected $tabs = [];

    /**
     * Tab constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * Append a tab section.
     *
     * @param string   $title
     * @param \Closure $callback
     * @param bool     $active
     *
     * @return $this
     */
    public function add($title, \Closure $callback, $active = false)
    {
        $this->tabs[] = new TabItem($title, $this->form, $callback, $active);
    }

    /**
     * Get all tabs.
     *
     * @return Collection
     */
    public function getTabs()
    {
        $hasActive = false;

        foreach ($this->tabs as $tab) {
            if ($tab->active) {
                $hasActive = true;
                break;
            }
        }

        // 如果没有active的tab，设置第一个为active
        if (!empty($this->tabs) && $hasActive === false) {
            $this->tabs[0]->active = true;
        }

        return $this->tabs;
    }

    /**
     * @return bool
     */
    public function isNotEmpty()
    {
        return !empty($this->tabs);
    }
}
