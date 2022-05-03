<?php

namespace Phrity\Pdo\Query;

class Table
{
    private $b;
    private $name;
    private $alias;

    /**
     * Create a Table instance
     * @param Builder $b          Builder to use
     * @param string $name        Name of table
     * @param string|null $alias  Alias of table (optional)
     * @return New Table instance
     */
    public function __construct(Builder $b, string $name, string $alias = null)
    {
        $this->b = $b;
        $this->name = $name;
        $this->alias = $alias;
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    /**
     * Build field associated to this table
     * @param string $name        Name of field
     * @param string|null $alias  Alias of field (optional)
     * @return New Field instance
     */
    public function field(string $name, string $alias = null): Field
    {
        return new Field($this->b, $this, $name, $alias);
    }


    /* ---------- Generator methods -------------------------------------------------- */

    /**
     * Return definition
     * @return string Sql table definition
     */
    public function define(bool $use_alias = false): string
    {
        return $use_alias && $this->alias
            ? "{$this->b->e($this->name)} {$this->b->e($this->alias)}"
            : $this->b->e($this->name);
    }

    /**
     * Return reference
     * @return string Sql table reference
     */
    public function refer(bool $use_alias = false): string
    {
        return $this->b->e($use_alias && $this->alias ? $this->alias : $this->name);
    }
}
