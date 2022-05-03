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

    public function testInsertUnescaped(): void
    {
        $b = new Builder($this->getPdo());

        $insert = $b->insert(
            $table = $b->table('table_name'),
            $b->assign($table->field('int_field'), $b->value(1234)),
            $b->assign($table->field('str_field'), $b->value('my string'))
        );
        $this->assertSame(
            'INSERT INTO table_name (int_field,str_field) VALUES (1234,\'my string\');',
            $insert->sql()
        );
    }

    public function testInsertEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);

        $insert = $b->insert(
            $table = $b->table('table_name'),
            $b->assign($table->field('int_field'), $b->value(1234)),
            $b->assign($table->field('str_field'), $b->value('my string'))
        );
        $this->assertSame(
            'INSERT INTO `table_name` (`int_field`,`str_field`) VALUES (1234,\'my string\');',
            $insert->sql()
        );
    }
}
