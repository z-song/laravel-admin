<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;

class Select extends Field
{
    public function render()
    {
        $this->script .= "$(\"#{$this->id}\").select2({allowClear: true});";

        return parent::render()->with(['options' => $this->options]);
    }

    public function options($options = [])
    {
        // remote options
        if (is_string($options)) {
            return call_user_func_array([$this, 'loadOptionsFromRemote'], func_get_args());
        }

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = (array) $options;

        return $this;
    }

    /**
     * Load options from remote
     *
     * @param string $url
     * @param array $parameters
     * @param array $options
     * @return $this
     */
    protected function loadOptionsFromRemote($url, $parameters = [], $options = [])
    {
        $ajaxOptions = [
            'url' => $url.'?'.http_build_query($parameters)
        ];

        $ajaxOptions = json_encode(array_merge($ajaxOptions, $options));

        $this->script .= <<<EOT

$.ajax($ajaxOptions).done(function(data) {
  $("#{$this->id}").select2({data: data});
});

EOT;

        return $this;
    }
}
