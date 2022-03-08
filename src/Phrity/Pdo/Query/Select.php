<?php

namespace Phrity\Pdo\Query;

class Select implements SqlInterface
{
    private $b;
    private $froms = [];
    private $selects = [];
    private $conditions = [];

    public function __construct(Builder $b, SqlInterface ...$references)
    {
        $this->b = $b;
        foreach ($references as $reference) {
            switch (get_class($reference)) {
                case 'Phrity\Pdo\Query\Table':
                    $this->addFrom($reference);
                    break;
                case 'Phrity\Pdo\Query\Field':
                case 'Phrity\Pdo\Query\Value':
                    $this->addSelect($reference);
                    break;
            }
        }
    }

    public function addFrom(Table $table): void
    {
        $this->froms[] = $table;
    }

    public function addSelect(SqlInterface $select): void
    {
        $this->selects[] = $select;
    }

    public function __toString(): string
    {
        $fields = $this->selects ? implode(',', array_map('strval', $this->selects)) : '*';
        $froms = $this->froms ? ' FROM ' . implode(',', array_map('strval', $this->froms)) : '';
//        $condition = $this->condition ? " WHERE {$this->condition}" : '';
        return "SELECT {$fields}{$froms};";
    }
}