<?php

namespace Encore\Admin\Form\Field;

/**
 * Class ListBox.
 *
 * @see https://github.com/istvan-ujjmeszaros/bootstrap-duallistbox
 */
class Listbox extends MultipleSelect
{
    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @param array $settings
     *
     * @return $this
     */
    public function settings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Set listbox height.
     *
     * @param int $height
     *
     * @return Listbox
     */
    public function height($height = 200)
    {
        return $this->settings(['selectorMinimalHeight' => $height]);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadRemoteOptions($url, $parameters = [], $options = [])
    {
        $remote = array_merge([
            'url' => $url.'?'.http_build_query($parameters),
        ], $options);

        return $this->addVariables(compact('remote'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $settings = array_merge([
            'infoText'              => trans('admin.listbox.text_total'),
            'infoTextEmpty'         => trans('admin.listbox.text_empty'),
            'infoTextFiltered'      => trans('admin.listbox.filtered'),
            'filterTextClear'       => trans('admin.listbox.filter_clear'),
            'filterPlaceHolder'     => trans('admin.listbox.filter_placeholder'),
            'selectorMinimalHeight' => 200,
        ], $this->settings);

        $this->addVariables([
            'options'  => $this->getOptions(),
            'settings' => $settings,
        ])->attribute('data-value', implode(',', (array) $this->value()));

        $this->addCascadeScript();

        return parent::fieldRender();
    }
}
