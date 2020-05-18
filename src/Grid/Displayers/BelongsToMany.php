<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Selectable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as Relation;
use Illuminate\Support\Arr;

class BelongsToMany extends AbstractDisplayer
{
    use BelongsToRelation;

    /**
     * Other key for many-to-many relation.
     *
     * @var string
     */
    protected static $otherKey = [];

    /**
     * @return $this
     */
    public function addScript()
    {
        $script = <<<SCRIPT
(function () {
    var modal = $('#{$this->modalID}');
    var related = null;
    var selected = [];

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('.select').iCheck({
                radioClass:'iradio_minimal-blue',
                checkboxClass:'icheckbox_minimal-blue'
            });
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index, el) {
                if ($.inArray($(el).val().toString(), selected) >=0 ) {
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
                {$this->columnName}: selected,
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
        selected = related.data('val').map(function (value) {
            return value.toString();
        });

        load("{$this->getLoadUrl(1)}");
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
        var val = $(this).val().toString();
        if (selected.indexOf(val) < 0) {
            selected.push(val);
        }
    }).on('ifUnchecked', 'input.select', function (e) {
           var val = $(this).val().toString();
           var index = selected.indexOf(val);
            if (index !== -1) {
                selected.splice(index, 1);
            }
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
     * Get other key for this many-to-many relation.
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getOtherKey()
    {
        if (isset(static::$otherKey[$this->getName()])) {
            return static::$otherKey[$this->getName()];
        }

        $model = $this->getGrid()->model()->getOriginalModel();

        if (is_callable([$model, $this->getName()]) &&
            ($relation = $model->{$this->getName()}()) instanceof Relation
        ) {
            /* @var Relation $relation */
            $fullKey = $relation->getQualifiedRelatedPivotKeyName();
            $fullKeyArray = explode('.', $fullKey);

            return static::$otherKey[$this->getName()] = end($fullKeyArray);
        }

        throw new \Exception('Column of this field must be a `BelongsToMany` relation.');
    }

    /**
     * @return false|string|void
     * @throws \Exception
     */
    protected function getOriginalData()
    {
        $relations = $this->getColumn()->getOriginal();

        if (is_string($relations)) {
            $data = explode(',', $relations);
        }

        if (!is_array($relations)) {
            return;
        }

        $first = current($relations);

        if (is_null($first)) {
            $data = null;

            // MultipleSelect value store as an ont-to-many relationship.
        } elseif (is_array($first)) {
            foreach ($relations as $relation) {
                $data[] = Arr::get($relation, "pivot.{$this->getOtherKey()}");
            }

            // MultipleSelect value store as a column.
        } else {
            $data = $relations;
        }

        return json_encode($data);
    }
}
