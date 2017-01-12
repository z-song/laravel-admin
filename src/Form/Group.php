<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Group
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Collection
     */
    protected $groups;

    /**
     * Tab constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->groups = new Collection();
    }

    /**
     * Add a tab section.
     *
     * @param string $title
     * @param \Closure $content
     *
     * @return $this
     */
    public function group($title, \Closure $content)
    {
        $id = 'group-'.($this->groups->count() + 1);

        $this->groups[$id] = compact('id', 'title', 'content');

        return $this;
    }

    /**
     * Get all tabs.
     *
     * @return Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }
}