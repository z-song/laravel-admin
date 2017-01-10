<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Illuminate\Support\Collection;

class Tab
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Collection
     */
    protected $tabs;

    /**
     * Tab constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->tabs = new Collection();
    }

    /**
     * Add a tab section.
     *
     * @param string $title
     * @param \Closure $content
     * @param boolean $active
     *
     * @return $this
     */
    public function tab($title, \Closure $content, $active = false)
    {
        $id = 'form-'.($this->tabs->count() + 1);

        $this->tabs->push(compact('id', 'title', 'content', 'active'));

        return $this;
    }

    /**
     * Get all tabs.
     *
     * @return Collection
     */
    public function getTabs()
    {
        // If there is no active tab, then active the first.
        if ($this->tabs->filter(function ($tab) {
            return $tab['active'];
        })->isEmpty()) {

            $first = $this->tabs->first();
            $first['active'] = true;

            $this->tabs->offsetSet(0, $first);
        }

        return $this->tabs;
    }
}