<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Illuminate\Support\Collection;

class Frame
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Collection
     */
    protected $frames;

    /**
     * Frame name.
     *
     * @var string
     */
    protected $name = 'frame';

    /**
     * Tab constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->frames = new Collection();
    }

    /**
     * Add a tab section.
     *
     * @param string   $title
     * @param \Closure $content
     *
     * @return $this
     */
    public function frame($title, \Closure $content)
    {
        $id = $this->name.'-'.($this->frames->count() + 1);

        $this->frames[$id] = compact('id', 'title', 'content');

        return $this;
    }

    /**
     * Get all tabs.
     *
     * @return Collection
     */
    public function getFrames()
    {
        return $this->frames;
    }
}
