<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

class ValuePicker
{
    /**
     * @var string
     */
    protected $modal;

    /**
     * @var Text
     */
    protected $field;

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $selecteable;

    /**
     * @var string
     */
    protected $separator;

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * ValuePicker constructor.
     * @param string $selecteable
     * @param string $column
     * @param bool $multiple
     * @param string $separator
     */
    public function __construct($selecteable, $column = '', $multiple = false, $separator = ',')
    {
        $this->selecteable = $selecteable;
        $this->column      = $column;
        $this->multiple    = $multiple;
        $this->separator   = $separator;
    }

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl()
    {
        $selectable = str_replace('\\', '_', $this->selecteable);

        $args = [$this->multiple, $this->column];

        return route('admin.handle-selectable', compact('selectable', 'args'));
    }

    protected function addStyle()
    {
        Admin::style(<<<STYLE
.file-picker.modal tr {
    cursor: pointer;
}
.file-picker.modal .box {
    border-top: none;
    margin-bottom: 0;
    box-shadow: none;
}
STYLE);

        if ($this->field instanceof File) {
            Admin::style(<<<STYLE
.file-picker.modal tr {
    cursor: pointer;
}
.file-picker.modal .box {
    border-top: none;
    margin-bottom: 0;
    box-shadow: none;
}

.picker-file-preview {
    overflow: hidden;
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 8px;
    width: 100%;
    margin-bottom: 5px;
}

.picker-file-preview .file-preview-frame {
    margin: 8px;file-content
    border: 1px solid rgba(0,0,0,.2);
    box-shadow: 0 0 10px 0 rgba(0,0,0,.2);
    padding: 6px;
    float: left;
    text-align: center;
    width: 213px;
}

.picker-file-preview .file-content {
    font-size: 6em;
}

.picker-file-preview .file-caption-info {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 160px;
    height: 15px;
    margin: auto;
}

.picker-file-preview .file-actions {
    text-align: right;
    margin-top: 20px;
}
STYLE);
        }
    }

    protected function addScript()
    {
        if ($this->multiple) {
            $script = <<<SCRIPT
;(function () {
    var separator = '{$this->separator}';
    var modal = $('#{$this->modal}');
    var value = $("{$this->field->getElementClassSelector()}").val().split(separator);

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('input.select').iCheck({
                radioClass:'iradio_minimal-blue',
                checkboxClass:'icheckbox_minimal-blue'
            });
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index, el) {
                if ($.inArray($(el).val().toString(), value) >=0 ) {
                    $(el).iCheck('toggle');
                }
            });
        });
    };

    modal.on('show.bs.modal', function (e) {
        load("{$this->getLoadUrl()}");
    }).on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('click', 'tr', function (e) {
        $(this).find('input.select').iCheck('toggle');
        e.preventDefault();
    }).on('submit', '.box-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        return false;
    }).on('ifChecked', 'input.select', function (e) {
        if (value.indexOf($(this).val()) < 0) {
            value.push($(this).val());
        }
    }).on('ifUnchecked', 'input.select', function (e) {
           var val = $(this).val();
           var index = value.indexOf(val);
           if (index !== -1) {
               value.splice(index, 1);
           }
    }).find('.modal-footer .submit').click(function () {
        $("{$this->field->getElementClassSelector()}").val(value.join(separator));
        modal.modal('toggle');
    });

})();
SCRIPT;

        } else {
            $script = <<<SCRIPT
;(function () {

    var modal = $('#{$this->modal}');
    var value = $("{$this->field->getElementClassSelector()}").val();

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('input.select').iCheck({
                radioClass:'iradio_minimal-blue',
                checkboxClass:'icheckbox_minimal-blue'
            });
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index, el) {
                if ($(el).val() == value) {
                    $(el).iCheck('toggle');
                }
            });
        });
    };

    modal.on('show.bs.modal', function (e) {
        load("{$this->getLoadUrl()}");
    }).on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('click', 'tr', function (e) {
        $(this).find('input.select').iCheck('toggle');
        e.preventDefault();
    }).on('submit', '.box-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        return false;
    }).on('ifChecked', 'input.select', function (e) {
        value = $(this).val();
    }).find('.modal-footer .submit').click(function () {
        $("{$this->field->getElementClassSelector()}").val(value);
        modal.modal('toggle');
    });

})();
SCRIPT;
        }

        Admin::script($script);
    }

    protected function addHtml()
    {
        $trans = [
            'choose' => admin_trans('admin.choose'),
            'cancal' => admin_trans('admin.cancel'),
            'submit' => admin_trans('admin.submit'),
        ];

        Admin::html(<<<HTML
<div class="modal fade file-picker" id="{$this->modal}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{$trans['choose']}</h4>
            </div>
            <div class="modal-body">
                <div class="loading text-center">
                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{$trans['cancal']}</button>
                <button type="button" class="btn btn-primary submit">{$trans['submit']}</button>
            </div>
        </div>
    </div>
</div>
HTML);
    }

    /**
     * @return $this
     */
    protected function addPickBtn()
    {
        $text = admin_trans('admin.browse');

        $this->field->addPickBtn(<<<HTML
<a class="btn btn-primary" data-toggle="modal" data-target="#{$this->modal}">
    <i class="fa fa-folder-open"></i>  {$text}
</a>
HTML);
    }

    public function mount(Field $field)
    {
        $this->field = $field;
        $this->modal = sprintf('picker-modal-%s', $field->getElementClassString());

        $this->addStyle();
        $this->addScript();
        $this->addHtml();
        $this->addPickBtn();
    }

    public function preview($field)
    {
        $value = $this->field->value();

        if ($this->multiple) {
            $value = explode($this->separator, $value);
        }

        $previews = [];

        foreach (Arr::wrap($value) as $item) {

            $content = $field == File::class ? '<i class="glyphicon glyphicon-file"></i>' : "<img src=\"{$item}\"/>";

            $previews[] = [
                'content' => $content,
                'caption' => $item,
            ];
        }

        return $previews;
    }
}
