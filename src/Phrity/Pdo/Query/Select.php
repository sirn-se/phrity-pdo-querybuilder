<?php

namespace Phrity\Pdo\Query;

class Select implements SqlInterface
{
    private $b;
    private $from;
    private $selects = [];
    private $condition;
    private $joins = [];
    private $forupdate = false;

    public function __construct(Builder $b, SqlInterface ...$references)
    {
        $this->b = $b;
        foreach ($references as $reference) {
            switch (get_class($reference)) {
                case 'Phrity\Pdo\Query\Table':
                    $this->setFrom($reference);
                    break;
                case 'Phrity\Pdo\Query\Field':
                case 'Phrity\Pdo\Query\Value':
                    $this->addSelect($reference);
                    break;
                case 'Phrity\Pdo\Query\AndCondition':
                case 'Phrity\Pdo\Query\Eq':
                    $this->setCondition($reference);
                    break;
            }
        }
    }

    public function setFrom(Table $table): void
    {
        $this->from = $table;
    }

    public function addSelect(SqlInterface $select): void
    {
        $this->selects[] = $select;
    }

    public function setCondition(SqlInterface $condition): void
    {
        $this->condition = $condition;
    }

    public function addInnerJoin(Table $join): InnerJoin
    {
        $join = $this->b->innerJoin($this->from, $join);
        $this->joins[] = $join;
        return $join;
    }

    public function forUpdate(): void
    {
        $this->forupdate = true;
    }

    public function sql(): string
    {
        $from = $this->from ? " FROM {$this->from->define()}" : '';
        $fields = $this->selects ? implode(',', array_map(function ($select) {
            return $select->define();
        }, $this->selects)) : '*';
        $joins = $this->joins ? implode(',', array_map(function ($join) {
            return $join->sql();
        }, $this->joins)) : '';
        $condition = $this->condition ? " WHERE {$this->condition->define()}" : '';
        $forupdate = $this->forupdate ? " FOR UPDATE" : '';
        return "SELECT {$fields}{$from}{$joins}{$condition}{$forupdate};";
    }

    public function __toString(): string
    {
        return $this->sql();
    }
}
