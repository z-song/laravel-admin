<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Selectable;

class BelongsTo extends AbstractDisplayer
{
    use BelongsToRelation;

    /**
     * @return $this
     */
    public function addScript()
    {
        $script = <<<SCRIPT
(function () {
    var modal = $('#{$this->modalID}');
    var related = null;
    var selected = {};

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('.select').iCheck({
                radioClass:'iradio_minimal-blue',
                checkboxClass:'icheckbox_minimal-blue'
            });
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index,    el) {
                if ($(el).val() == selected.id) {
                    $(el).iCheck('toggle');
                }
            });
        });
    };

    var update = function (callback) {
        $.ajax({
            url: "{$this->getResource()}/" + related.attr('key'),
            type: "POST",
            data: {
                {$this->columnName}: selected.id,
                _token: LA.token,
                _method: 'PUT'
            },
            success: function (data) {
                callback(data);
            }
        });
    };

    modal.on('show.bs.modal', function (e) {
        related = $(e.relatedTarget);
        selected.id = related.data('val');
        load("{$this->getLoadUrl()}");
    }).on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('click', 'tr', function (e) {
        $(this).find('input.select').iCheck('toggle');
        e.preventDefault();
    }).on('submit', '.box-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        e.preventDefault();
    }).on('ifChecked', 'input.select', function (e) {
        selected.id = $(this).val();
    }).find('.modal-footer .submit').click(function () {
        update(function (data) {
            related.data('val', selected);
            related.find('.text').html(data.display["{$this->columnName}"]);
            related.find('a').toggleClass('text-green text-muted');

            setTimeout(function () {
                related.find('a').toggleClass('text-green text-muted');
            }, 2000);

            modal.modal('toggle');

            toastr.success(data.message);
        });
    });
})();
SCRIPT;

        Admin::script($script);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getOriginalData()
    {
        return $this->getColumn()->getOriginal();
    }
}
