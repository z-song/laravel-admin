<?php

namespace Encore\Admin\Table\Filter\Presenter;

use Illuminate\Contracts\Support\Arrayable;

class Radio extends Presenter
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * Display inline.
     *
     * @var bool
     */
    protected $inline = true;

    /**
     * Radio constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = (array) $options;

        admin_assets_require('icheck');

        return $this;
    }

    /**
     * Draw stacked radios.
     *
     * @return $this
     */
    public function stacked(): self
    {
        $this->inline = false;

        return $this;
    }

    /**
     * @return array
     */
    public function variables(): array
    {
        return [
            'options' => $this->options,
            'inline'  => $this->inline,
        ];
    }
}
