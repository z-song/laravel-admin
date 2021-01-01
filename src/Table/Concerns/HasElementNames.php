<?php

namespace Encore\Admin\Table\Concerns;

trait HasElementNames
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $name;

    /**
     * HTML element names.
     *
     * @var array
     */
    protected $elementNames = [
        //        'table_row'        => 'table-row',
        //        'table_select_all' => 'table-select-all',
        'table_per_page'   => 'table-per-pager',
        'table_batch'      => 'table-batch',
        'export_selected'  => 'export-selected',
        'selected_rows'    => 'selectedRows',
    ];

    /**
     * Set name to table.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        $this->model()->setPerPageName("{$name}_{$this->model()->getPerPageName()}");

        $this->getFilter()->setName($name);

        return $this;
    }

    /**
     * Get name of table.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
//    public function getTableRowName()
//    {
//        return $this->elementNameWithPrefix('table_row');
//    }

    /**
     * @return string
     */
//    public function getSelectAllName()
//    {
//        return $this->elementNameWithPrefix('table_select_all');
//    }

    /**
     * @return string
     */
    public function getPerPageName()
    {
        return $this->elementNameWithPrefix('table_per_page');
    }

    /**
     * @return string
     */
    public function getTableBatchName()
    {
        return $this->elementNameWithPrefix('table_batch');
    }

    /**
     * @return string
     */
    public function getExportSelectedName()
    {
        return $this->elementNameWithPrefix('export_selected');
    }

    /**
     * @return string
     */
    public function getSelectedRowsName()
    {
        $elementName = $this->elementNames['selected_rows'];

        if ($this->name) {
            return sprintf('%s%s', $this->name, ucfirst($elementName));
        }

        return $elementName;
    }

    /**
     * @return string
     */
    protected function elementNameWithPrefix($name)
    {
        $elementName = $this->elementNames[$name];

        if ($this->name) {
            return sprintf('%s-%s', $this->name, $elementName);
        }

        return $elementName;
    }
}
