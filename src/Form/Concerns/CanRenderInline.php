<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field;
use Symfony\Component\DomCrawler\Crawler;

trait CanRenderInline
{
    /**
     * @var bool
     */
    protected $asInline = false;

    public function setAsInline()
    {
        $this->asInline = true;
    }

    public function renderInline()
    {
        $crawler = new Crawler($this->render());

        return $crawler->filter('.field-control')->html();
    }

    public function renderInform()
    {
        if ($this->asInline) {
            return '';
        }

        return $this->render();
    }
}
