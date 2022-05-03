<?php

declare(strict_types=1);

namespace Phrity\Pdo\Query;

use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    use PdoTrait;

    public function setUp(): void
    {
        error_reporting(-1);
    }

    public function testUpdateUnescaped(): void
    {
        $b = new Builder($this->getPdo());

        $update = $b->update(
            $table = $b->table('table_name'),
            $b->assign($table->field('int_field'), $b->value(1234)),
            $b->assign($table->field('str_field'), $b->value('my string'))
        );
        $this->assertSame(
            'UPDATE table_name SET int_field=1234,str_field=\'my string\';',
            $update->sql()
        );

        $update->where($b->and(
            $b->eq($table->field('field_name'), $b->value(123)),
            $b->eq($table->field('another_name'), $b->now()),
        ));
        $this->assertSame(
            'UPDATE table_name SET int_field=1234,str_field=\'my string\' '
            . 'WHERE ((field_name=123) AND (another_name=NOW()));',
            $update->sql()
        );
    }

    public function testUpdateEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);

        $update = $b->update(
            $table = $b->table('table_name'),
            $b->assign($table->field('int_field'), $b->value(1234)),
            $b->assign($table->field('str_field'), $b->value('my string'))
        );
        $this->assertSame(
            'UPDATE `table_name` SET `int_field`=1234,`str_field`=\'my string\';',
            $update->sql()
        );

        $update->where($b->and(
            $b->eq($table->field('field_name'), $b->value(123)),
            $b->eq($table->field('another_name'), $b->now()),
        ));
        $this->assertSame(
            'UPDATE `table_name` SET `int_field`=1234,`str_field`=\'my string\' '
            . 'WHERE ((`field_name`=123) AND (`another_name`=NOW()));',
            $update->sql()
        );
    }
}
