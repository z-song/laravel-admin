<?php

namespace Encore\Admin\Table\Tools;

use Encore\Admin\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Query\Builder;

class Header extends AbstractTool
{
    /**
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * Header constructor.
     *
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Get model query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function queryBuilder()
    {
        if (!$this->queryBuilder) {
            $this->queryBuilder = $this->table->model()->getQueryBuilder();
        }

        return $this->queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $content = call_user_func($this->table->header(), $this->queryBuilder());

        if (empty($content)) {
            return '';
        }

        if ($content instanceof Renderable) {
            $content = $content->render();
        }

        if ($content instanceof Htmlable) {
            $content = $content->toHtml();
        }

        return <<<HTML
    <div class="card-header clearfix">
        {$content}
    </div>
HTML;
    }
}
