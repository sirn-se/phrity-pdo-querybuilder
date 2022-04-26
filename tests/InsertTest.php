<?php

declare(strict_types=1);

namespace Phrity\Pdo\Query;

use PHPUnit\Framework\TestCase;

class InsertTest extends TestCase
{
    use PdoTrait;

    public function setUp(): void
    {
        error_reporting(-1);
    }

    public function testSelect(): void
    {
        $b = new Builder($this->getPdo());

        $insert = $b->insert(
            $table = $b->table('table_name'),
            $b->assign($table->field('int_field'), $b->value(1234)),
            $b->assign($table->field('str_field'), $b->value('my string'))
        );
        $this->assertSame(
            'INSERT INTO table_name (table_name.int_field,table_name.str_field) VALUES (1234,\'my string\');',
            $insert->sql()
        );

        $insert->where($b->and(
            $b->eq($table->field('field_name'), $b->value(123)),
            $b->eq($table->field('another_name'), $b->now()),
        ));
        $this->assertSame(
            'INSERT INTO table_name (table_name.int_field,table_name.str_field) VALUES (1234,\'my string\') '
            . 'WHERE ((table_name.field_name=123) AND (table_name.another_name=NOW()));',
            $insert->sql()
        );
    }
}
