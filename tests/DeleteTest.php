<?php

declare(strict_types=1);

namespace Phrity\Pdo\Query;

use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    use PdoTrait;

    public function setUp(): void
    {
        error_reporting(-1);
    }

    public function testDeleteUnescaped(): void
    {
        $b = new Builder($this->getPdo());

        $delete = $b->delete(
            $table = $b->table('table_name'),
        );
        $delete->where($b->and(
            $b->eq($table->field('field_name'), $b->value(123)),
            $b->eq($table->field('another_name'), $b->now()),
        ));
        $this->assertSame(
            'DELETE FROM table_name WHERE ((field_name=123) AND (another_name=NOW()));',
            $delete->sql()
        );
    }

    public function testDeleteEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);

        $delete = $b->delete(
            $table = $b->table('table_name'),
        );
        $delete->where($b->and(
            $b->eq($table->field('field_name'), $b->value(123)),
            $b->eq($table->field('another_name'), $b->now()),
        ));
        $this->assertSame(
            'DELETE FROM `table_name` WHERE ((`field_name`=123) AND (`another_name`=NOW()));',
            $delete->sql()
        );
    }
}
