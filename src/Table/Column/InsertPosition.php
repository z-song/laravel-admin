<?php

namespace Encore\Admin\Table\Column;

use Encore\Admin\Table\Column;

/**
 * @mixin Column
 */
trait InsertPosition
{
    /**
     * @param string $name
     *
     * @return $this
     */
    public function insertAfter($name)
    {
        return $this->insert($this->findPositionByName($name) + 1);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function insertBefore($name)
    {
        return $this->insert($this->findPositionByName($name));
    }

    /**
     * @param int $position
     *
     * @return $this
     */
    public function insert($position)
    {
        $columns = $this->table->columns();

        if ($position < 0 || $position > $columns->count() - 1) {
            throw new \InvalidArgumentException('Invalid column position');
        }

        $columns->splice($position, 0, [$this]);
        $columns->pop();

        return $this;
    }

    /**
     * @param string $name
     *
     * @return int
     */
    protected function findPositionByName($name)
    {
        $position = -1;

        foreach ($this->table->columns() as $index => $column) {
            if ($column->getName() == $name) {
                $position = $index;
                break;
            }
        }

        if ($position < 0) {
            throw new \InvalidArgumentException("Column [$name] not found");
        }

        return $position;
    }
}
