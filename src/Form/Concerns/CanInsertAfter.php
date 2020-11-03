<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field;
use Symfony\Component\DomCrawler\Crawler;

trait CanInsertAfter
{
    /**
     * @var array
     */
    protected $after;

    /**
     * @var bool
     */
    protected $asAfter = false;

    /**
     * @param \Closure $callback
     *
     * @return $this
     */
    public function after(\Closure $callback)
    {
        $this->form->beginInsertAfter($this);

        call_user_func($callback, $this->form);

        $this->form->endInsertAfter();

        $this->addVariables('after', $this->after);

        return $this;
    }

    public function setAsAfter()
    {
        $this->asAfter = true;
    }

    public function insertAfter(Field $content)
    {
        $this->after[] = $content;

        $content->setAsAfter(true);
    }

    public function renderAfter()
    {
        $crawler = new Crawler($this->render());

        return $crawler->filter('.field-control>.col')->outerHtml();
    }

    public function renderInform()
    {
        if ($this->asAfter) {
            return '';
        }

        return $this->render();
    }
}
