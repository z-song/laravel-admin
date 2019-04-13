<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Text extends Field
{
    use PlainInput;

    /**
     * @var string
     */
    protected $icon = 'fa-pencil';

    /**
     * Set custom fa-icon.
     *
     * @param string $icon
     *
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->initPlainInput();

        $this->prepend('<i class="fa '.$this->icon.' fa-fw"></i>')
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', old($this->elementName ?: $this->column, $this->value()))
            ->defaultAttribute('class', 'form-control '.$this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        $this->addVariables([
            'prepend' => $this->prepend,
            'append'  => $this->append,
        ]);

        return parent::render();
    }

    /**
     * Add inputmask to an elements.
     *
     * @param array $options
     *
     * @return $this
     */
    public function inputmask($options)
    {
        $options = $this->json_encode_options($options);

        $this->script = "$('{$this->getElementClassSelector()}').inputmask($options);";

        return $this;
    }

    /**
     * Encode options to Json.
     *
     * @param array $options
     *
     * @return $json
     */
    protected function json_encode_options($options)
    {
        $data = $this->prepare_options($options);

        $json = json_encode($data['options']);

        $json = str_replace($data['toReplace'], $data['original'], $json);

        return $json;
    }

    /**
     * Prepare options.
     *
     * @param array $options
     *
     * @return array
     */
    protected function prepare_options($options)
    {
        $original = [];
        $toReplace = [];

        foreach ($options as $key => &$value) {
            if (is_array($value)) {
                $subArray = $this->prepare_options($value);
                $value = $subArray['options'];
                $original = array_merge($original, $subArray['original']);
                $toReplace = array_merge($toReplace, $subArray['toReplace']);
            } elseif (preg_match('/function.*?/', $value)) {
                $original[] = $value;
                $value = "%{$key}%";
                $toReplace[] = "\"{$value}\"";
            }
        }

        return compact('original', 'toReplace', 'options');
    }

    /**
     * Add datalist element to Text input.
     *
     * @param array $entries
     *
     * @return $this
     */
    public function datalist($entries = [])
    {
        $this->defaultAttribute('list', "list-{$this->id}");

        $datalist = "<datalist id=\"list-{$this->id}\">";
        foreach ($entries as $k => $v) {
            $datalist .= "<option value=\"{$k}\">{$v}</option>";
        }
        $datalist .= '</datalist>';

        return $this->append($datalist);
    }
}
