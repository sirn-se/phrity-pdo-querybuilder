<?php

declare(strict_types=1);

namespace Phrity\Pdo\Query;

use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    use PdoTrait;

    public function setUp(): void
    {
        error_reporting(-1);
    }

    public function testTableUnescaped(): void
    {
        $b = new Builder($this->getPdo(), false);

        $table_1 = $b->table('table_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Table', $table_1);
        $this->assertSame('table_name', $table_1->define(false));
        $this->assertSame('table_name', $table_1->refer(false));
        $this->assertSame('table_name', $table_1->define(true));
        $this->assertSame('table_name', $table_1->refer(true));

        $table_2 = $b->table('table_name', 'tn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Table', $table_2);
        $this->assertSame('table_name', $table_2->define(false));
        $this->assertSame('table_name', $table_2->refer(false));
        $this->assertSame('table_name tn', $table_2->define(true));
        $this->assertSame('tn', $table_2->refer(true));
    }

    public function testTableEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);

        $table_1 = $b->table('table_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Table', $table_1);
        $this->assertSame('`table_name`', $table_1->define(false));
        $this->assertSame('`table_name`', $table_1->refer(false));
        $this->assertSame('`table_name`', $table_1->define(true));
        $this->assertSame('`table_name`', $table_1->refer(true));

        $table_2 = $b->table('table_name', 'tn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Table', $table_2);
        $this->assertSame('`table_name`', $table_2->define(false));
        $this->assertSame('`table_name`', $table_2->refer(false));
        $this->assertSame('`table_name` `tn`', $table_2->define(true));
        $this->assertSame('`tn`', $table_2->refer(true));
    }

    public function testFieldUnescaped(): void
    {
        $b = new Builder($this->getPdo(), false);

        $field_1 = $b->table('table_name')->field('field_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field_1);
        $this->assertSame('field_name', $field_1->define(false, false));
        $this->assertSame('field_name', $field_1->refer(false, false));
        $this->assertSame('table_name.field_name', $field_1->define(false, true));
        $this->assertSame('table_name.field_name', $field_1->refer(false, true));
        $this->assertSame('field_name', $field_1->define(true, false));
        $this->assertSame('field_name', $field_1->refer(true, false));
        $this->assertSame('table_name.field_name', $field_1->define(true, true));
        $this->assertSame('table_name.field_name', $field_1->refer(true, true));

        $field_2 = $b->table('table_name', 'tn')->field('field_name', 'fn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field_2);
        $this->assertSame('field_name', $field_2->define(false, false));
        $this->assertSame('field_name', $field_2->refer(false, false));
        $this->assertSame('table_name.field_name', $field_2->define(false, true));
        $this->assertSame('table_name.field_name', $field_2->refer(false, true));
        $this->assertSame('field_name fn', $field_2->define(true, false));
        $this->assertSame('fn', $field_2->refer(true, false));
        $this->assertSame('tn.field_name fn', $field_2->define(true, true));
        $this->assertSame('tn.fn', $field_2->refer(true, true));
    }

    public function testFieldEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);

        $field_1 = $b->table('table_name')->field('field_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field_1);
        $this->assertSame('`field_name`', $field_1->define(false, false));
        $this->assertSame('`field_name`', $field_1->refer(false, false));
        $this->assertSame('`table_name`.`field_name`', $field_1->define(false, true));
        $this->assertSame('`table_name`.`field_name`', $field_1->refer(false, true));
        $this->assertSame('`field_name`', $field_1->define(true, false));
        $this->assertSame('`field_name`', $field_1->refer(true, false));
        $this->assertSame('`table_name`.`field_name`', $field_1->define(true, true));
        $this->assertSame('`table_name`.`field_name`', $field_1->refer(true, true));

        $field_2 = $b->table('table_name', 'tn')->field('field_name', 'fn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field_2);
        $this->assertSame('`field_name`', $field_2->define(false, false));
        $this->assertSame('`field_name`', $field_2->refer(false, false));
        $this->assertSame('`table_name`.`field_name`', $field_2->define(false, true));
        $this->assertSame('`table_name`.`field_name`', $field_2->refer(false, true));
        $this->assertSame('`field_name` `fn`', $field_2->define(true, false));
        $this->assertSame('`fn`', $field_2->refer(true, false));
        $this->assertSame('`tn`.`field_name` `fn`', $field_2->define(true, true));
        $this->assertSame('`tn`.`fn`', $field_2->refer(true, true));
    }

    public function testValuesUnescaped(): void
    {
        $b = new Builder($this->getPdo(), false);

        $str_value = $b->value('my string');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $str_value);
        $this->assertSame('\'my string\'', $str_value->define(false));
        $this->assertSame('\'my string\'', $str_value->refer(false));
        $this->assertSame('\'my string\'', $str_value->define(true));
        $this->assertSame('\'my string\'', $str_value->refer(true));

        $int_value = $b->value(1234);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $int_value);
        $this->assertSame('1234', $int_value->define(false));
        $this->assertSame('1234', $int_value->refer(false));
        $this->assertSame('1234', $int_value->define(true));
        $this->assertSame('1234', $int_value->refer(true));

        $float_value = $b->value(12.34);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $float_value);
        $this->assertSame('12.34', $float_value->define(false));
        $this->assertSame('12.34', $float_value->refer(false));
        $this->assertSame('12.34', $float_value->define(true));
        $this->assertSame('12.34', $float_value->refer(true));

        $bool_value = $b->value(false);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $bool_value);
        $this->assertSame('0', $bool_value->define(false));
        $this->assertSame('0', $bool_value->refer(false));
        $this->assertSame('0', $bool_value->define(true));
        $this->assertSame('0', $bool_value->refer(true));

        $bool_value = $b->value(true);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $bool_value);
        $this->assertSame('1', $bool_value->define(false));
        $this->assertSame('1', $bool_value->refer(false));
        $this->assertSame('1', $bool_value->define(true));
        $this->assertSame('1', $bool_value->refer(true));

        $null_value = $b->value(null);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $null_value);
        $this->assertSame('NULL', $null_value->define(false));
        $this->assertSame('NULL', $null_value->refer(false));
        $this->assertSame('NULL', $null_value->define(true));
        $this->assertSame('NULL', $null_value->refer(true));

        $str_value = $b->value('my string', 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $str_value);
        $this->assertSame('\'my string\'', $str_value->define(false));
        $this->assertSame('\'my string\'', $str_value->refer(false));
        $this->assertSame('\'my string\' mv', $str_value->define(true));
        $this->assertSame('mv', $str_value->refer(true));

        $int_value = $b->value(1234, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $int_value);
        $this->assertSame('1234', $int_value->define(false));
        $this->assertSame('1234', $int_value->refer(false));
        $this->assertSame('1234 mv', $int_value->define(true));
        $this->assertSame('mv', $int_value->refer(true));

        $float_value = $b->value(12.34, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $float_value);
        $this->assertSame('12.34', $float_value->define(false));
        $this->assertSame('12.34', $float_value->refer(false));
        $this->assertSame('12.34 mv', $float_value->define(true));
        $this->assertSame('mv', $float_value->refer(true));

        $bool_value = $b->value(false, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $bool_value);
        $this->assertSame('0', $bool_value->define(false));
        $this->assertSame('0', $bool_value->refer(false));
        $this->assertSame('0 mv', $bool_value->define(true));
        $this->assertSame('mv', $bool_value->refer(true));

        $bool_value = $b->value(true, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $bool_value);
        $this->assertSame('1', $bool_value->define(false));
        $this->assertSame('1', $bool_value->refer(false));
        $this->assertSame('1 mv', $bool_value->define(true));
        $this->assertSame('mv', $bool_value->refer(true));

        $null_value = $b->value(null, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $null_value);
        $this->assertSame('NULL', $null_value->define(false));
        $this->assertSame('NULL', $null_value->refer(false));
        $this->assertSame('NULL mv', $null_value->define(true));
        $this->assertSame('mv', $null_value->refer(true));
    }

    public function testValuesEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);

        $str_value = $b->value('my string');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $str_value);
        $this->assertSame('\'my string\'', $str_value->define(false));
        $this->assertSame('\'my string\'', $str_value->refer(false));
        $this->assertSame('\'my string\'', $str_value->define(true));
        $this->assertSame('\'my string\'', $str_value->refer(true));

        $int_value = $b->value(1234);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $int_value);
        $this->assertSame('1234', $int_value->define(false));
        $this->assertSame('1234', $int_value->refer(false));
        $this->assertSame('1234', $int_value->define(true));
        $this->assertSame('1234', $int_value->refer(true));

        $float_value = $b->value(12.34);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $float_value);
        $this->assertSame('12.34', $float_value->define(false));
        $this->assertSame('12.34', $float_value->refer(false));
        $this->assertSame('12.34', $float_value->define(true));
        $this->assertSame('12.34', $float_value->refer(true));

        $bool_value = $b->value(false);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $bool_value);
        $this->assertSame('0', $bool_value->define(false));
        $this->assertSame('0', $bool_value->refer(false));
        $this->assertSame('0', $bool_value->define(true));
        $this->assertSame('0', $bool_value->refer(true));

        $bool_value = $b->value(true);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $bool_value);
        $this->assertSame('1', $bool_value->define(false));
        $this->assertSame('1', $bool_value->refer(false));
        $this->assertSame('1', $bool_value->define(true));
        $this->assertSame('1', $bool_value->refer(true));

        $null_value = $b->value(null);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $null_value);
        $this->assertSame('NULL', $null_value->define(false));
        $this->assertSame('NULL', $null_value->refer(false));
        $this->assertSame('NULL', $null_value->define(true));
        $this->assertSame('NULL', $null_value->refer(true));

        $str_value = $b->value('my string', 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $str_value);
        $this->assertSame('\'my string\'', $str_value->define(false));
        $this->assertSame('\'my string\'', $str_value->refer(false));
        $this->assertSame('\'my string\' `mv`', $str_value->define(true));
        $this->assertSame('`mv`', $str_value->refer(true));

        $int_value = $b->value(1234, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $int_value);
        $this->assertSame('1234', $int_value->define(false));
        $this->assertSame('1234', $int_value->refer(false));
        $this->assertSame('1234 `mv`', $int_value->define(true));
        $this->assertSame('`mv`', $int_value->refer(true));

        $float_value = $b->value(12.34, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $float_value);
        $this->assertSame('12.34', $float_value->define(false));
        $this->assertSame('12.34', $float_value->refer(false));
        $this->assertSame('12.34 `mv`', $float_value->define(true));
        $this->assertSame('`mv`', $float_value->refer(true));

        $bool_value = $b->value(false, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $bool_value);
        $this->assertSame('0', $bool_value->define(false));
        $this->assertSame('0', $bool_value->refer(false));
        $this->assertSame('0 `mv`', $bool_value->define(true));
        $this->assertSame('`mv`', $bool_value->refer(true));

        $bool_value = $b->value(true, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $bool_value);
        $this->assertSame('1', $bool_value->define(false));
        $this->assertSame('1', $bool_value->refer(false));
        $this->assertSame('1 `mv`', $bool_value->define(true));
        $this->assertSame('`mv`', $bool_value->refer(true));

        $null_value = $b->value(null, 'mv');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $null_value);
        $this->assertSame('NULL', $null_value->define(false));
        $this->assertSame('NULL', $null_value->refer(false));
        $this->assertSame('NULL `mv`', $null_value->define(true));
        $this->assertSame('`mv`', $null_value->refer(true));
    }

    public function testConditionsUnescaped(): void
    {
        $b = new Builder($this->getPdo(), false);
        $field = $b->table('table_name', 'tn')->field('field_name', 'fn');
        $str_value = $b->value('my string');
        $int_value = $b->value(1234);

        $eq = $b->eq($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\EqExpression', $eq);
        $this->assertSame('(field_name=\'my string\')', $eq->refer(false, false));
        $this->assertSame('(table_name.field_name=\'my string\')', $eq->refer(false, true));
        $this->assertSame('(fn=\'my string\')', $eq->refer(true, false));
        $this->assertSame('(tn.fn=\'my string\')', $eq->refer(true, true));

        $neq = $b->neq($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\NeqExpression', $neq);
        $this->assertSame('(field_name<>\'my string\')', $neq->refer(false, false));
        $this->assertSame('(table_name.field_name<>\'my string\')', $neq->refer(false, true));
        $this->assertSame('(fn<>\'my string\')', $neq->refer(true, false));
        $this->assertSame('(tn.fn<>\'my string\')', $neq->refer(true, true));

        $gt = $b->gt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GtExpression', $gt);
        $this->assertSame('(field_name>1234)', $gt->refer(false, false));
        $this->assertSame('(table_name.field_name>1234)', $gt->refer(false, true));
        $this->assertSame('(fn>1234)', $gt->refer(true, false));
        $this->assertSame('(tn.fn>1234)', $gt->refer(true, true));

        $gte = $b->gte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GteExpression', $gte);
        $this->assertSame('(field_name>=1234)', $gte->refer(false, false));
        $this->assertSame('(table_name.field_name>=1234)', $gte->refer(false, true));
        $this->assertSame('(fn>=1234)', $gte->refer(true, false));
        $this->assertSame('(tn.fn>=1234)', $gte->refer(true, true));

        $lt = $b->lt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LtExpression', $lt);
        $this->assertSame('(field_name<1234)', $lt->refer(false, false));
        $this->assertSame('(table_name.field_name<1234)', $lt->refer(false, true));
        $this->assertSame('(fn<1234)', $lt->refer(true, false));
        $this->assertSame('(tn.fn<1234)', $lt->refer(true, true));

        $lte = $b->lte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LteExpression', $lte);
        $this->assertSame('(field_name<=1234)', $lte->refer(false, false));
        $this->assertSame('(table_name.field_name<=1234)', $lte->refer(false, true));
        $this->assertSame('(fn<=1234)', $lte->refer(true, false));
        $this->assertSame('(tn.fn<=1234)', $lte->refer(true, true));
    }

    public function testConditionsEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);
        $field = $b->table('table_name', 'tn')->field('field_name', 'fn');
        $str_value = $b->value('my string');
        $int_value = $b->value(1234);

        $eq = $b->eq($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\EqExpression', $eq);
        $this->assertSame('(`field_name`=\'my string\')', $eq->refer(false, false));
        $this->assertSame('(`table_name`.`field_name`=\'my string\')', $eq->refer(false, true));
        $this->assertSame('(`fn`=\'my string\')', $eq->refer(true, false));
        $this->assertSame('(`tn`.`fn`=\'my string\')', $eq->refer(true, true));

        $neq = $b->neq($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\NeqExpression', $neq);
        $this->assertSame('(`field_name`<>\'my string\')', $neq->refer(false, false));
        $this->assertSame('(`table_name`.`field_name`<>\'my string\')', $neq->refer(false, true));
        $this->assertSame('(`fn`<>\'my string\')', $neq->refer(true, false));
        $this->assertSame('(`tn`.`fn`<>\'my string\')', $neq->refer(true, true));

        $gt = $b->gt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GtExpression', $gt);
        $this->assertSame('(`field_name`>1234)', $gt->refer(false, false));
        $this->assertSame('(`table_name`.`field_name`>1234)', $gt->refer(false, true));
        $this->assertSame('(`fn`>1234)', $gt->refer(true, false));
        $this->assertSame('(`tn`.`fn`>1234)', $gt->refer(true, true));

        $gte = $b->gte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GteExpression', $gte);
        $this->assertSame('(`field_name`>=1234)', $gte->refer(false, false));
        $this->assertSame('(`table_name`.`field_name`>=1234)', $gte->refer(false, true));
        $this->assertSame('(`fn`>=1234)', $gte->refer(true, false));
        $this->assertSame('(`tn`.`fn`>=1234)', $gte->refer(true, true));

        $lt = $b->lt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LtExpression', $lt);
        $this->assertSame('(`field_name`<1234)', $lt->refer(false, false));
        $this->assertSame('(`table_name`.`field_name`<1234)', $lt->refer(false, true));
        $this->assertSame('(`fn`<1234)', $lt->refer(true, false));
        $this->assertSame('(`tn`.`fn`<1234)', $lt->refer(true, true));

        $lte = $b->lte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LteExpression', $lte);
        $this->assertSame('(`field_name`<=1234)', $lte->refer(false, false));
        $this->assertSame('(`table_name`.`field_name`<=1234)', $lte->refer(false, true));
        $this->assertSame('(`fn`<=1234)', $lte->refer(true, false));
        $this->assertSame('(`tn`.`fn`<=1234)', $lte->refer(true, true));
    }

    public function testFunctionsUnescaped(): void
    {
        $b = new Builder($this->getPdo(), false);

        $now_func = $b->now();
        $this->assertInstanceOf('Phrity\Pdo\Query\NowFunction', $now_func);
        $this->assertSame('NOW()', $now_func->refer());

        $curdate_func = $b->curDate();
        $this->assertInstanceOf('Phrity\Pdo\Query\CurDateFunction', $curdate_func);
        $this->assertSame('CURDATE()', $curdate_func->refer());
    }

    public function testFunctionsEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);

        $now_func = $b->now();
        $this->assertInstanceOf('Phrity\Pdo\Query\NowFunction', $now_func);
        $this->assertSame('NOW()', $now_func->refer());

        $curdate_func = $b->curDate();
        $this->assertInstanceOf('Phrity\Pdo\Query\CurDateFunction', $curdate_func);
        $this->assertSame('CURDATE()', $curdate_func->refer());
    }

    public function testConditionSetsUnescaped(): void
    {
        $b = new Builder($this->getPdo(), false);
        $table = $b->table('table_name', 'tn');
        $eq = $b->eq($table->field('field_name_1', 'fn_1'), $b->value('my string'));
        $neq = $b->neq($table->field('field_name_2', 'fn_2'), $b->value(1234));

        $and_expr = $b->and($eq, $neq);
        $this->assertInstanceOf('Phrity\Pdo\Query\AndExpression', $and_expr);
        $this->assertSame(
            '((field_name_1=\'my string\') AND (field_name_2<>1234))',
            $and_expr->refer(false, false)
        );
        $this->assertSame(
            '((table_name.field_name_1=\'my string\') AND (table_name.field_name_2<>1234))',
            $and_expr->refer(false, true)
        );
        $this->assertSame(
            '((fn_1=\'my string\') AND (fn_2<>1234))',
            $and_expr->refer(true, false)
        );
        $this->assertSame(
            '((tn.fn_1=\'my string\') AND (tn.fn_2<>1234))',
            $and_expr->refer(true, true)
        );

        $or_expr = $b->or($eq, $neq);
        $this->assertInstanceOf('Phrity\Pdo\Query\OrExpression', $or_expr);
        $this->assertSame(
            '((field_name_1=\'my string\') OR (field_name_2<>1234))',
            $or_expr->refer(false, false)
        );
        $this->assertSame(
            '((table_name.field_name_1=\'my string\') OR (table_name.field_name_2<>1234))',
            $or_expr->refer(false, true)
        );
        $this->assertSame(
            '((fn_1=\'my string\') OR (fn_2<>1234))',
            $or_expr->refer(true, false)
        );
        $this->assertSame(
            '((tn.fn_1=\'my string\') OR (tn.fn_2<>1234))',
            $or_expr->refer(true, true)
        );
    }

    public function testConditionSetsEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);
        $table = $b->table('table_name', 'tn');
        $eq = $b->eq($table->field('field_name_1', 'fn_1'), $b->value('my string'));
        $neq = $b->neq($table->field('field_name_2', 'fn_2'), $b->value(1234));

        $and_expr = $b->and($eq, $neq);
        $this->assertInstanceOf('Phrity\Pdo\Query\AndExpression', $and_expr);
        $this->assertSame(
            '((`field_name_1`=\'my string\') AND (`field_name_2`<>1234))',
            $and_expr->refer(false, false)
        );
        $this->assertSame(
            '((`table_name`.`field_name_1`=\'my string\') AND (`table_name`.`field_name_2`<>1234))',
            $and_expr->refer(false, true)
        );
        $this->assertSame(
            '((`fn_1`=\'my string\') AND (`fn_2`<>1234))',
            $and_expr->refer(true, false)
        );
        $this->assertSame(
            '((`tn`.`fn_1`=\'my string\') AND (`tn`.`fn_2`<>1234))',
            $and_expr->refer(true, true)
        );

        $or_expr = $b->or($eq, $neq);
        $this->assertInstanceOf('Phrity\Pdo\Query\OrExpression', $or_expr);
        $this->assertSame(
            '((`field_name_1`=\'my string\') OR (`field_name_2`<>1234))',
            $or_expr->refer(false, false)
        );
        $this->assertSame(
            '((`table_name`.`field_name_1`=\'my string\') OR (`table_name`.`field_name_2`<>1234))',
            $or_expr->refer(false, true)
        );
        $this->assertSame(
            '((`fn_1`=\'my string\') OR (`fn_2`<>1234))',
            $or_expr->refer(true, false)
        );
        $this->assertSame(
            '((`tn`.`fn_1`=\'my string\') OR (`tn`.`fn_2`<>1234))',
            $or_expr->refer(true, true)
        );
    }

    public function testAssignUnescaped(): void
    {
        $b = new Builder($this->getPdo(), false);
        $field = $b->table('table_name', 'tn')->field('field_name', 'fn');
        $str_value = $b->value('my string');

        $assign = $b->assign($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\Assign', $assign);
        $this->assertSame('\'my string\'', $assign->source(false, false));
        $this->assertSame('field_name', $assign->target(false, false));
        $this->assertSame('field_name=\'my string\'', $assign->define(false, false));
        $this->assertSame('\'my string\'', $assign->source(false, true));
        $this->assertSame('table_name.field_name', $assign->target(false, true));
        $this->assertSame('table_name.field_name=\'my string\'', $assign->define(false, true));
        $this->assertSame('\'my string\'', $assign->source(true, false));
        $this->assertSame('fn', $assign->target(true, false));
        $this->assertSame('fn=\'my string\'', $assign->define(true, false));
        $this->assertSame('\'my string\'', $assign->source(true, true));
        $this->assertSame('tn.fn', $assign->target(true, true));
        $this->assertSame('tn.fn=\'my string\'', $assign->define(true, true));
    }

    public function testAssignEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);
        $field = $b->table('table_name', 'tn')->field('field_name', 'fn');
        $str_value = $b->value('my string');

        $assign = $b->assign($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\Assign', $assign);
        $this->assertSame('\'my string\'', $assign->source(false, false));
        $this->assertSame('`field_name`', $assign->target(false, false));
        $this->assertSame('`field_name`=\'my string\'', $assign->define(false, false));
        $this->assertSame('\'my string\'', $assign->source(false, true));
        $this->assertSame('`table_name`.`field_name`', $assign->target(false, true));
        $this->assertSame('`table_name`.`field_name`=\'my string\'', $assign->define(false, true));
        $this->assertSame('\'my string\'', $assign->source(true, false));
        $this->assertSame('`fn`', $assign->target(true, false));
        $this->assertSame('`fn`=\'my string\'', $assign->define(true, false));
        $this->assertSame('\'my string\'', $assign->source(true, true));
        $this->assertSame('`tn`.`fn`', $assign->target(true, true));
        $this->assertSame('`tn`.`fn`=\'my string\'', $assign->define(true, true));
    }

    public function testSortLimitUnescaped(): void
    {
        $b = new Builder($this->getPdo(), false);
        $field = $b->table('table_name', 'tn')->field('field_name', 'fn');

        $asc = $b->asc($field);
        $this->assertInstanceOf('Phrity\Pdo\Query\AscSort', $asc);
        $this->assertSame('field_name ASC', $asc->define(false, false));
        $this->assertSame('table_name.field_name ASC', $asc->define(false, true));
        $this->assertSame('fn ASC', $asc->define(true, false));
        $this->assertSame('tn.fn ASC', $asc->define(true, true));

        $desc = $b->desc($field);
        $this->assertInstanceOf('Phrity\Pdo\Query\DescSort', $desc);
        $this->assertSame('field_name DESC', $desc->define(false, false));
        $this->assertSame('table_name.field_name DESC', $desc->define(false, true));
        $this->assertSame('fn DESC', $desc->define(true, false));
        $this->assertSame('tn.fn DESC', $desc->define(true, true));

        $limit = $b->limit();
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('', $limit->define());
        $limit = $b->limit(10);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 10', $limit->define());
        $limit = $b->limit(10, 5);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 5,10', $limit->define());
    }

    public function testSortLimitEscaped(): void
    {
        $b = new Builder($this->getPdo(), true);
        $field = $b->table('table_name', 'tn')->field('field_name', 'fn');

        $asc = $b->asc($field);
        $this->assertInstanceOf('Phrity\Pdo\Query\AscSort', $asc);
        $this->assertSame('`field_name` ASC', $asc->define(false, false));
        $this->assertSame('`table_name`.`field_name` ASC', $asc->define(false, true));
        $this->assertSame('`fn` ASC', $asc->define(true, false));
        $this->assertSame('`tn`.`fn` ASC', $asc->define(true, true));

        $desc = $b->desc($field);
        $this->assertInstanceOf('Phrity\Pdo\Query\DescSort', $desc);
        $this->assertSame('`field_name` DESC', $desc->define(false, false));
        $this->assertSame('`table_name`.`field_name` DESC', $desc->define(false, true));
        $this->assertSame('`fn` DESC', $desc->define(true, false));
        $this->assertSame('`tn`.`fn` DESC', $desc->define(true, true));

        $limit = $b->limit();
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('', $limit->define());
        $limit = $b->limit(10);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 10', $limit->define());
        $limit = $b->limit(10, 5);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 5,10', $limit->define());
    }

    public function testQuoting(): void
    {
        $b = new Builder($this->getPdo());

        $this->assertSame("'My string'", $b->q('My string'));
        $this->assertSame(1234, $b->q(1234));
        $this->assertSame(12.34, $b->q(12.34));
        $this->assertSame(1, $b->q(true));
        $this->assertSame('NULL', $b->q(null));
        $this->assertSame([3], $b->q([3]));
        $this->assertSame("`My string`", $b->escape('My string'));
    }
}
