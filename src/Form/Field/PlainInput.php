<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

/**
 * Trait PlainInput.
 *
 * @mixin Text
 */
trait PlainInput
{
    /**
     * @var string
     */
    protected $prepend;

    /**
     * @var string
     */
    protected $append;

    /**
     * @param mixed $prepend
     *
     * @return $this
     */
    public function prepend($prepend)
    {
        if ($prepend instanceof Field) {
            $prepend->bePrepend = true;
            $this->form->pushField($prepend);
        }

        if (is_null($this->prepend)) {
            $this->prepend = $prepend;
        }

        return $this;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function prependText($text)
    {
        return $this->prepend("<span class=\"input-group-text\">{$text}</span>");
    }

    /**
     * @param mixed $string
     *
     * @return $this
     */
    public function append($string)
    {
        if (is_null($this->append)) {
            $this->append = $string;
        }

        return $this;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function appendText($text)
    {
        return $this->append("<span class=\"input-group-text\">{$text}</span>");
    }

    /**
     * @return void
     */
    protected function initPlainInput()
    {
        if (empty($this->view)) {
            $this->view = 'admin::form.input';
        }
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    protected function defaultAttribute($attribute, $value)
    {
        if (!array_key_exists($attribute, $this->attributes)) {
            $this->attribute($attribute, $value);
        }

        return $this;
    }
}
