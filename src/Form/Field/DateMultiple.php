<?php

namespace Encore\Admin\Form\Field;

class DateMultiple extends Text
{
    protected static $css = [
        'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
        'https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.3.0/dist/themes/light.min.css',

    ];

    protected static $js = [
        'https://cdn.jsdelivr.net/npm/flatpickr',
        'https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/shortcut-buttons-flatpickr.min.js',
        'https://npmcdn.com/flatpickr@4.6.6/dist/l10n/zh.js',

         
    ];

    protected $format = 'YYYY-MM-DD';

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function prepare($value)
    {
        if ($value === '') {
            $value = null;
        }

        return $value;
    }

    public function render()
    {
        $this->options['format'] = $this->format;
        $this->options['locale'] = array_key_exists('locale', $this->options) ? $this->options['locale'] : config('app.locale');
        $this->options['allowInputToggle'] = true;

        $this->script = "$('{$this->getElementClassSelector()}').flatpickr({mode: 'multiple',dateFormat: 'Y-m-d', locale: 'zh', plugins: [
            ShortcutButtonsPlugin({
              button: {
                label: 'Clear',
              },
              onClick: (index, fp) => {
                fp.clear();
                fp.close();
              }
            })
          ]});";

        $this->prepend('<i class="fa fa-calendar fa-fw"></i>')
            ->defaultAttribute('style', 'width: 100%');

        return parent::render();
    }
}
