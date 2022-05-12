<?php

namespace Phrity\Pdo\Query;

class Insert implements StatementInterface
{
    private $b;
    private $table;
    private $assign = [];

    public function __construct(Builder $b, Table $table, Assign ...$assign)
    {
        $this->b = $b;
        $this->table($table);
        $this->assign(...$assign);
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    public function table(Table $table): Table
    {
        $this->table = $table;
        return $table;
    }

    public function assign(Assign ...$assign): self
    {
        $this->assign = $assign;
        return $this;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function sql(): string
    {
        $targets = $this->assign ? ' (' . implode(',', array_map(function ($assign) {
            return $assign->target();
        }, $this->assign)) . ')' : '';
        $sources = $this->assign ? ' VALUES (' . implode(',', array_map(function ($assign) {
            return $assign->source();
        }, $this->assign)) . ')' : '';
        return "INSERT INTO {$this->table->refer()}{$targets}{$sources};";
    }
}
