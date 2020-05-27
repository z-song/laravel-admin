<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Renderable;

class Modal extends AbstractDisplayer
{
    /**
     * @var string
     */
    protected $renderable;

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl()
    {
        $renderable = str_replace('\\', '_', $this->renderable);

        return route('admin.handle-renderable', compact('renderable'));
    }

    protected function addRenderableModalScript()
    {
        $script = <<<SCRIPT
(function () {
    var modal = $('.grid-modal');

    modal.on('show.bs.modal', function (e) {
        var key = $(e.relatedTarget).data('key');
        $.get('{$this->getLoadUrl()}'+'&key='+key, function (data) {
            modal.find('.modal-body').html(data);
        });
    })
})();
SCRIPT;

        Admin::script($script);
    }

    public function display($callback = null)
    {
        if (func_num_args() == 2) {
            list($title, $callback) = func_get_args();
        } elseif (func_num_args() == 1) {
            $title = $this->trans('title');
        }

        if (is_subclass_of($callback, Renderable::class)) {
            $html = <<<'HTML'
<div class="loading text-center">
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
</div>
HTML;
            $this->renderable = $callback;
            $this->addRenderableModalScript();
        } else {
            $callback = $callback->bindTo($this->row);
            $html = call_user_func_array($callback, [$this->row]);
        }

        $key = $this->getKey().'-'.str_replace('.', '_', $this->getColumn()->getName());

        return <<<EOT
<span data-toggle="modal" data-target="#grid-modal-{$key}" data-key="{$this->getKey()}">
   <a href="javascript:void(0)"><i class="fa fa-clone"></i>&nbsp;&nbsp;{$this->value}</a>
</span>

<div class="modal grid-modal" id="grid-modal-{$key}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{$title}</h4>
      </div>
      <div class="modal-body">
        {$html}
      </div>
    </div>
  </div>
</div>

EOT;
    }
}
