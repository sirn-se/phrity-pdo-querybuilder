<?php

namespace Phrity\Pdo\Query;

class AndCondition implements SqlInterface
{
    private $contitions;

    public function __construct(SqlInterface ...$contitions)
    {
        $this->contitions = $contitions;
    }

    public function define(): string
    {
        return '(' . implode(' AND ', array_map(function ($contition) {
            return $contition->define();
        }, $this->contitions)) . ')';
    }
}
