<?php

namespace Encore\Admin\Form\Field;

class Mobile extends Text
{
    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'mask' => '99999999999',
    ];

    public function render()
    {
        $options = $this->json_encode_options($this->options);

        $this->script = <<<EOT

$('{$this->getElementClassSelector()}').inputmask($options);
EOT;

        $this->prepend('<i class="fa fa-phone"></i>')
            ->defaultAttribute('style', 'width: 150px');

        return parent::render();
    }
    
    protected function json_encode_options($options)
    {
        $value_arr = [];
        $replace_keys = [];

        foreach ($options as $key => &$value) {
            // Look for values starting with 'function('
            if (strpos($value, 'function(') === 0) {
                // Store function string.
                $value_arr[] = $value;
                // Replace function string in $foo with a 'unique' special key.
                $value = '%' . $key . '%';
                // Later on, we'll look for the value, and replace it.
                $replace_keys[] = '"' . $value . '"';
            }
        }

        // Now encode the array to json format
        $json = json_encode($options);

        // Replace the special keys with the original string.
        $json = str_replace($replace_keys, $value_arr, $json);

        return $json;
    }
}
