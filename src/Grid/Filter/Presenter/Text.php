<?php

namespace Encore\Admin\Grid\Filter\Presenter;

use Encore\Admin\Admin;

class Text extends Presenter
{
    /**
     * @var string
     */
    protected $placeholder = '';

    /**
     * @var string
     */
    protected $icon = 'pencil';

    /**
     * @var string
     */
    protected $type = 'text';

    /**
     * Text constructor.
     *
     * @param string $placeholder
     */
    public function __construct($placeholder = '')
    {
        $this->placeholder($placeholder);
    }

    /**
     * Get variables for field template.
     *
     * @return array
     */
    public function variables() : array
    {
        return [
            'placeholder' => $this->placeholder,
            'icon'        => $this->icon,
            'type'        => $this->type,
        ];
    }

    /**
     * Set input placeholder.
     *
     * @param string $placeholder
     *
     * @return $this
     */
    public function placeholder($placeholder = '') : Text
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return Text
     */
    public function url() : Text
    {
        return $this->inputmask(['alias' => 'url'], 'internet-explorer');
    }

    /**
     * @return Text
     */
    public function email() : Text
    {
        return $this->inputmask(['alias' => 'email'], 'envelope');
    }

    /**
     * @return Text
     */
    public function integer() : Text
    {
        return $this->inputmask(['alias' => 'integer']);
    }

    /**
     * @param array $options
     *
     * @see https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
     *
     * @return Text
     */
    public function decimal($options = []) : Text
    {
        return $this->inputmask(array_merge($options, ['alias' => 'decimal']));
    }

    /**
     * @param array $options
     *
     * @see https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
     *
     * @return Text
     */
    public function currency($options = []) : Text
    {
        return $this->inputmask(array_merge($options, [
            'alias'                 => 'currency',
            'prefix'                => '',
            'removeMaskOnSubmit'    => true,
        ]));
    }

    /**
     * @param array $options
     *
     * @see https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
     *
     * @return Text
     */
    public function percentage($options = [])
    {
        $options = array_merge(['alias' => 'percentage'], $options);

        return $this->inputmask($options);
    }

    /**
     * @return Text
     */
    public function ip() : Text
    {
        return $this->inputmask(['alias' => 'ip'], 'laptop');
    }

    /**
     * @return Text
     */
    public function mac() : Text
    {
        return $this->inputmask(['alias' => 'mac'], 'laptop');
    }

    /**
     * @param string $mask
     *
     * @return Text
     */
    public function mobile($mask = '19999999999') : Text
    {
        return $this->inputmask(compact('mask'), 'phone');
    }

    /**
     * @param array $options
     * @param string $icon
     *
     * @return $this
     */
    public function inputmask($options = [], $icon = 'pencil') : Text
    {
        $options = json_encode($options);

        Admin::script("$('#filter-modal input.{$this->filter->getId()}').inputmask($options);");

        $this->icon = $icon;

        return $this;
    }
}
