<?php
/**
 * Created by PhpStorm.
 * User: encore
 * Date: 15/12/26
 * Time: 下午10:15
 */

namespace Encore\Admin\Pagination;


use Illuminate\Pagination\BootstrapThreePresenter;

class AdminThreePresenter extends BootstrapThreePresenter
{
    public function render()
    {
        if ($this->hasPages()) {
            return sprintf(
                '<ul class="pagination pagination-sm no-margin pull-right">%s %s %s</ul>',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            );
        }

        return '';
    }
}