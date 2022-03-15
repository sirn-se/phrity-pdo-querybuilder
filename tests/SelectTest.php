<?php

declare(strict_types=1);

namespace Phrity\Pdo\Query;

use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    use PdoTrait;

    public function setUp(): void
    {
        error_reporting(-1);
    }

    public function testSelect(): void
    {
        $b = new Builder($this->getPdo());

        $select = $b->select();
        $this->assertSame('SELECT *;', $select->sql());

        $select = $b->select($table = $b->table('table_name'));
        $this->assertSame('SELECT * FROM table_name;', $select->sql());

        $select = $b->select(null, $b->value(1234), $b->value('abc'));
        $this->assertSame('SELECT 1234,\'abc\';', $select->sql());

        $select = $b->select($table = $b->table('table_name'), $table->field('field_name'));
        $this->assertSame('SELECT table_name.field_name FROM table_name;', $select->sql());

        $select->where($b->and(
            $b->eq($table->field('field_name'), $b->value(123))
        ));
        $this->assertSame(
            'SELECT table_name.field_name FROM table_name WHERE ((table_name.field_name=123));',
            $select->sql()
        );

        $select->where($b->and(
            $b->eq($table->field('field_name'), $b->value(123)),
            $b->eq($table->field('another_name'), $b->now()),
        ));
        $this->assertSame(
            'SELECT table_name.field_name FROM table_name '
            . 'WHERE ((table_name.field_name=123) AND (table_name.another_name=NOW()));',
            $select->sql()
        );

        $inner = $select->innerJoin($b->table('inner_table'));
        $inner->on($b->eq($inner->field('inner_field'), $table->field('field_name')));
        $this->assertSame(
            'SELECT table_name.field_name FROM table_name '
            . 'INNER JOIN inner_table ON (inner_table.inner_field=table_name.field_name) '
            . 'WHERE ((table_name.field_name=123) AND (table_name.another_name=NOW()));',
            $select->sql()
        );

        $left = $select->leftJoin($b->table('left_table'));
        $left->on($b->eq($left->field('left_field'), $table->field('field_name')));
        $this->assertSame(
            'SELECT table_name.field_name FROM table_name '
            . 'INNER JOIN inner_table ON (inner_table.inner_field=table_name.field_name) '
            . 'LEFT JOIN left_table ON (left_table.left_field=table_name.field_name) '
            . 'WHERE ((table_name.field_name=123) AND (table_name.another_name=NOW()));',
            $select->sql()
        );

        $select->orderBy($left->field('left_field'), $b->eq($inner->field('inner_field'), $table->field('field_name')));
        $this->assertSame(
            'SELECT table_name.field_name FROM table_name '
            . 'INNER JOIN inner_table ON (inner_table.inner_field=table_name.field_name) '
            . 'LEFT JOIN left_table ON (left_table.left_field=table_name.field_name) '
            . 'WHERE ((table_name.field_name=123) AND (table_name.another_name=NOW())) '
            . 'ORDER BY left_table.left_field,(inner_table.inner_field=table_name.field_name);',
            $select->sql()
        );

        $select->forUpdate();
        $this->assertSame(
            'SELECT table_name.field_name FROM table_name '
            . 'INNER JOIN inner_table ON (inner_table.inner_field=table_name.field_name) '
            . 'LEFT JOIN left_table ON (left_table.left_field=table_name.field_name) '
            . 'WHERE ((table_name.field_name=123) AND (table_name.another_name=NOW())) '
            . 'ORDER BY left_table.left_field,(inner_table.inner_field=table_name.field_name) '
            . 'FOR UPDATE;',
            $select->sql()
        );
    }
}
