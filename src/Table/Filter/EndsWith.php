<?php

namespace Encore\Admin\Table\Filter;

class EndsWith extends Like
{
    protected $exprFormat = '%{value}';
}
