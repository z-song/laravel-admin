<?php

namespace Encore\Admin\Table\Filter;

class StartsWith extends Like
{
    protected $exprFormat = '{value}%';
}
