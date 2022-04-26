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

    public function testBasic(): void
    {
        $b = new Builder($this->getPdo());

        $table = $b->table('table_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Table', $table);
        $this->assertSame('table_name', $table->define());
        $this->assertSame('table_name', $table->refer());

        $field = $table->field('field_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field);
        $this->assertSame('table_name.field_name', $field->define());
        $this->assertSame('table_name.field_name', $field->refer());

        $field = $b->field($table, 'field_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field);
        $this->assertSame('table_name.field_name', $field->define());
        $this->assertSame('table_name.field_name', $field->refer());

        $str_value = $b->value('my string');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $str_value);
        $this->assertSame('\'my string\'', $str_value->define());
        $this->assertSame('\'my string\'', $str_value->refer());

        $int_value = $b->value(1234);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $int_value);
        $this->assertSame('1234', $int_value->define());
        $this->assertSame('1234', $int_value->refer());

        $eq_cond = $b->eq($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\EqExpression', $eq_cond);
        $this->assertSame('(table_name.field_name=\'my string\')', $eq_cond->define());
        $this->assertSame('(table_name.field_name=\'my string\')', $eq_cond->refer());

        $gt_cond = $b->gt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GtExpression', $gt_cond);
        $this->assertSame('(table_name.field_name>1234)', $gt_cond->define());
        $this->assertSame('(table_name.field_name>1234)', $gt_cond->refer());

        $gte_cond = $b->gte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GteExpression', $gte_cond);
        $this->assertSame('(table_name.field_name>=1234)', $gte_cond->define());
        $this->assertSame('(table_name.field_name>=1234)', $gte_cond->refer());

        $lt_cond = $b->lt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LtExpression', $lt_cond);
        $this->assertSame('(table_name.field_name<1234)', $lt_cond->define());
        $this->assertSame('(table_name.field_name<1234)', $lt_cond->refer());

        $lte_cond = $b->lte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LteExpression', $lte_cond);
        $this->assertSame('(table_name.field_name<=1234)', $lte_cond->define());
        $this->assertSame('(table_name.field_name<=1234)', $lte_cond->refer());

        $now_func = $b->now();
        $this->assertInstanceOf('Phrity\Pdo\Query\NowFunction', $now_func);
        $this->assertSame('NOW()', $now_func->define());
        $this->assertSame('NOW()', $now_func->refer());

        $curdate_func = $b->curDate();
        $this->assertInstanceOf('Phrity\Pdo\Query\CurDateFunction', $curdate_func);
        $this->assertSame('CURDATE()', $curdate_func->define());
        $this->assertSame('CURDATE()', $curdate_func->refer());

        $and_expr = $b->and($eq_cond, $gte_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\AndExpression', $and_expr);
        $this->assertSame(
            '((table_name.field_name=\'my string\') AND (table_name.field_name>=1234))',
            $and_expr->define()
        );
        $this->assertSame(
            '((table_name.field_name=\'my string\') AND (table_name.field_name>=1234))',
            $and_expr->refer()
        );

        $or_expr = $b->or($eq_cond, $gte_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\OrExpression', $or_expr);
        $this->assertSame(
            '((table_name.field_name=\'my string\') OR (table_name.field_name>=1234))',
            $or_expr->define()
        );
        $this->assertSame(
            '((table_name.field_name=\'my string\') OR (table_name.field_name>=1234))',
            $or_expr->refer()
        );

        $assign = $b->assign($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\Assign', $assign);
        $this->assertSame('table_name.field_name=\'my string\'', $assign->define());

        $asc = $b->asc($field);
        $this->assertInstanceOf('Phrity\Pdo\Query\AscSort', $asc);
        $this->assertSame('table_name.field_name ASC', $asc->define());

        $desc = $b->desc($eq_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\DescSort', $desc);
        $this->assertSame('(table_name.field_name=\'my string\') DESC', $desc->define());

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

    public function testAlias(): void
    {
        $b = new Builder($this->getPdo());

        $table = $b->table('table_name', 'tn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Table', $table);
        $this->assertSame('table_name tn', $table->define());
        $this->assertSame('tn', $table->refer());

        $field = $table->field('field_name', 'fn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field);
        $this->assertSame('tn.field_name fn', $field->define());
        $this->assertSame('tn.field_name', $field->refer());

        $field = $b->field($table, 'field_name', 'fn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field);
        $this->assertSame('tn.field_name fn', $field->define());
        $this->assertSame('tn.field_name', $field->refer());

        $str_value = $b->value('my string', 'ms');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $str_value);
        $this->assertSame('\'my string\' ms', $str_value->define());
        $this->assertSame('\'my string\'', $str_value->refer());

        $int_value = $b->value(1234, 'mi');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $int_value);
        $this->assertSame('1234 mi', $int_value->define());
        $this->assertSame('1234', $int_value->refer());

        $eq_cond = $b->eq($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\EqExpression', $eq_cond);
        $this->assertSame('(tn.field_name=\'my string\')', $eq_cond->define());
        $this->assertSame('(tn.field_name=\'my string\')', $eq_cond->refer());

        $gt_cond = $b->gt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GtExpression', $gt_cond);
        $this->assertSame('(tn.field_name>1234)', $gt_cond->define());
        $this->assertSame('(tn.field_name>1234)', $gt_cond->refer());

        $gte_cond = $b->gte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GteExpression', $gte_cond);
        $this->assertSame('(tn.field_name>=1234)', $gte_cond->define());
        $this->assertSame('(tn.field_name>=1234)', $gte_cond->refer());

        $lt_cond = $b->lt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LtExpression', $lt_cond);
        $this->assertSame('(tn.field_name<1234)', $lt_cond->define());
        $this->assertSame('(tn.field_name<1234)', $lt_cond->refer());

        $lte_cond = $b->lte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LteExpression', $lte_cond);
        $this->assertSame('(tn.field_name<=1234)', $lte_cond->define());
        $this->assertSame('(tn.field_name<=1234)', $lte_cond->refer());

        $now_func = $b->now($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\NowFunction', $now_func);
        $this->assertSame('NOW()', $now_func->define());
        $this->assertSame('NOW()', $now_func->refer());

        $curdate_func = $b->curDate();
        $this->assertInstanceOf('Phrity\Pdo\Query\CurDateFunction', $curdate_func);
        $this->assertSame('CURDATE()', $curdate_func->define());
        $this->assertSame('CURDATE()', $curdate_func->refer());

        $and_expr = $b->and($eq_cond, $gte_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\AndExpression', $and_expr);
        $this->assertSame(
            '((tn.field_name=\'my string\') AND (tn.field_name>=1234))',
            $and_expr->define()
        );
        $this->assertSame(
            '((tn.field_name=\'my string\') AND (tn.field_name>=1234))',
            $and_expr->refer()
        );

        $or_expr = $b->or($eq_cond, $gte_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\OrExpression', $or_expr);
        $this->assertSame(
            '((tn.field_name=\'my string\') OR (tn.field_name>=1234))',
            $or_expr->define()
        );
        $this->assertSame(
            '((tn.field_name=\'my string\') OR (tn.field_name>=1234))',
            $or_expr->refer()
        );

        $assign = $b->assign($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\Assign', $assign);
        $this->assertSame('tn.field_name=\'my string\'', $assign->define());

        $asc = $b->asc($field);
        $this->assertInstanceOf('Phrity\Pdo\Query\AscSort', $asc);
        $this->assertSame('tn.field_name ASC', $asc->define());

        $desc = $b->desc($eq_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\DescSort', $desc);
        $this->assertSame('(tn.field_name=\'my string\') DESC', $desc->define());

        $limit = $b->limit(10);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 10', $limit->define());

        $limit = $b->limit(10);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 10', $limit->define());

        $limit = $b->limit(10, 5);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 5,10', $limit->define());
    }

    public function testEscapedBasic(): void
    {
        $b = new Builder($this->getPdo(), true);

        $table = $b->table('table_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Table', $table);
        $this->assertSame('`table_name`', $table->define());
        $this->assertSame('`table_name`', $table->refer());

        $field = $table->field('field_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field);
        $this->assertSame('`table_name`.`field_name`', $field->define());
        $this->assertSame('`table_name`.`field_name`', $field->refer());

        $field = $b->field($table, 'field_name');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field);
        $this->assertSame('`table_name`.`field_name`', $field->define());
        $this->assertSame('`table_name`.`field_name`', $field->refer());

        $str_value = $b->value('my string');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $str_value);
        $this->assertSame('\'my string\'', $str_value->define());
        $this->assertSame('\'my string\'', $str_value->refer());

        $int_value = $b->value(1234);
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $int_value);
        $this->assertSame('1234', $int_value->define());
        $this->assertSame('1234', $int_value->refer());

        $eq_cond = $b->eq($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\EqExpression', $eq_cond);
        $this->assertSame('(`table_name`.`field_name`=\'my string\')', $eq_cond->define());
        $this->assertSame('(`table_name`.`field_name`=\'my string\')', $eq_cond->refer());

        $gt_cond = $b->gt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GtExpression', $gt_cond);
        $this->assertSame('(`table_name`.`field_name`>1234)', $gt_cond->define());
        $this->assertSame('(`table_name`.`field_name`>1234)', $gt_cond->refer());

        $gte_cond = $b->gte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GteExpression', $gte_cond);
        $this->assertSame('(`table_name`.`field_name`>=1234)', $gte_cond->define());
        $this->assertSame('(`table_name`.`field_name`>=1234)', $gte_cond->refer());

        $lt_cond = $b->lt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LtExpression', $lt_cond);
        $this->assertSame('(`table_name`.`field_name`<1234)', $lt_cond->define());
        $this->assertSame('(`table_name`.`field_name`<1234)', $lt_cond->refer());

        $lte_cond = $b->lte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LteExpression', $lte_cond);
        $this->assertSame('(`table_name`.`field_name`<=1234)', $lte_cond->define());
        $this->assertSame('(`table_name`.`field_name`<=1234)', $lte_cond->refer());

        $now_func = $b->now($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\NowFunction', $now_func);
        $this->assertSame('NOW()', $now_func->define());
        $this->assertSame('NOW()', $now_func->refer());

        $curdate_func = $b->curDate();
        $this->assertInstanceOf('Phrity\Pdo\Query\CurDateFunction', $curdate_func);
        $this->assertSame('CURDATE()', $curdate_func->define());
        $this->assertSame('CURDATE()', $curdate_func->refer());

        $and_expr = $b->and($eq_cond, $gte_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\AndExpression', $and_expr);
        $this->assertSame(
            '((`table_name`.`field_name`=\'my string\') AND (`table_name`.`field_name`>=1234))',
            $and_expr->define()
        );
        $this->assertSame(
            '((`table_name`.`field_name`=\'my string\') AND (`table_name`.`field_name`>=1234))',
            $and_expr->refer()
        );

        $or_expr = $b->or($eq_cond, $gte_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\OrExpression', $or_expr);
        $this->assertSame(
            '((`table_name`.`field_name`=\'my string\') OR (`table_name`.`field_name`>=1234))',
            $or_expr->define()
        );
        $this->assertSame(
            '((`table_name`.`field_name`=\'my string\') OR (`table_name`.`field_name`>=1234))',
            $or_expr->refer()
        );

        $assign = $b->assign($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\Assign', $assign);
        $this->assertSame('`table_name`.`field_name`=\'my string\'', $assign->define());

        $asc = $b->asc($field);
        $this->assertInstanceOf('Phrity\Pdo\Query\AscSort', $asc);
        $this->assertSame('`table_name`.`field_name` ASC', $asc->define());

        $desc = $b->desc($eq_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\DescSort', $desc);
        $this->assertSame('(`table_name`.`field_name`=\'my string\') DESC', $desc->define());

        $limit = $b->limit(10);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 10', $limit->define());

        $limit = $b->limit(10);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 10', $limit->define());

        $limit = $b->limit(10, 5);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 5,10', $limit->define());
    }

    public function testEscapedAlias(): void
    {
        $b = new Builder($this->getPdo(), true);

        $table = $b->table('table_name', 'tn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Table', $table);
        $this->assertSame('`table_name` `tn`', $table->define());
        $this->assertSame('`tn`', $table->refer());

        $field = $table->field('field_name', 'fn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field);
        $this->assertSame('`tn`.`field_name` `fn`', $field->define());
        $this->assertSame('`tn`.`field_name`', $field->refer());

        $field = $b->field($table, 'field_name', 'fn');
        $this->assertInstanceOf('Phrity\Pdo\Query\Field', $field);
        $this->assertSame('`tn`.`field_name` `fn`', $field->define());
        $this->assertSame('`tn`.`field_name`', $field->refer());

        $str_value = $b->value('my string', 'ms');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $str_value);
        $this->assertSame('\'my string\' `ms`', $str_value->define());
        $this->assertSame('\'my string\'', $str_value->refer());

        $int_value = $b->value(1234, 'mi');
        $this->assertInstanceOf('Phrity\Pdo\Query\Value', $int_value);
        $this->assertSame('1234 `mi`', $int_value->define());
        $this->assertSame('1234', $int_value->refer());

        $eq_cond = $b->eq($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\EqExpression', $eq_cond);
        $this->assertSame('(`tn`.`field_name`=\'my string\')', $eq_cond->define());
        $this->assertSame('(`tn`.`field_name`=\'my string\')', $eq_cond->refer());

        $gt_cond = $b->gt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GtExpression', $gt_cond);
        $this->assertSame('(`tn`.`field_name`>1234)', $gt_cond->define());
        $this->assertSame('(`tn`.`field_name`>1234)', $gt_cond->refer());

        $gte_cond = $b->gte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\GteExpression', $gte_cond);
        $this->assertSame('(`tn`.`field_name`>=1234)', $gte_cond->define());
        $this->assertSame('(`tn`.`field_name`>=1234)', $gte_cond->refer());

        $lt_cond = $b->lt($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LtExpression', $lt_cond);
        $this->assertSame('(`tn`.`field_name`<1234)', $lt_cond->define());
        $this->assertSame('(`tn`.`field_name`<1234)', $lt_cond->refer());

        $lte_cond = $b->lte($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\LteExpression', $lte_cond);
        $this->assertSame('(`tn`.`field_name`<=1234)', $lte_cond->define());
        $this->assertSame('(`tn`.`field_name`<=1234)', $lte_cond->refer());

        $now_func = $b->now($field, $int_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\NowFunction', $now_func);
        $this->assertSame('NOW()', $now_func->define());
        $this->assertSame('NOW()', $now_func->refer());

        $curdate_func = $b->curDate();
        $this->assertInstanceOf('Phrity\Pdo\Query\CurDateFunction', $curdate_func);
        $this->assertSame('CURDATE()', $curdate_func->define());
        $this->assertSame('CURDATE()', $curdate_func->refer());

        $and_expr = $b->and($eq_cond, $gte_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\AndExpression', $and_expr);
        $this->assertSame(
            '((`tn`.`field_name`=\'my string\') AND (`tn`.`field_name`>=1234))',
            $and_expr->define()
        );
        $this->assertSame(
            '((`tn`.`field_name`=\'my string\') AND (`tn`.`field_name`>=1234))',
            $and_expr->refer()
        );

        $or_expr = $b->or($eq_cond, $gte_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\OrExpression', $or_expr);
        $this->assertSame(
            '((`tn`.`field_name`=\'my string\') OR (`tn`.`field_name`>=1234))',
            $or_expr->define()
        );
        $this->assertSame(
            '((`tn`.`field_name`=\'my string\') OR (`tn`.`field_name`>=1234))',
            $or_expr->refer()
        );

        $assign = $b->assign($field, $str_value);
        $this->assertInstanceOf('Phrity\Pdo\Query\Assign', $assign);
        $this->assertSame('`tn`.`field_name`=\'my string\'', $assign->define());

        $asc = $b->asc($field);
        $this->assertInstanceOf('Phrity\Pdo\Query\AscSort', $asc);
        $this->assertSame('`tn`.`field_name` ASC', $asc->define());

        $desc = $b->desc($eq_cond);
        $this->assertInstanceOf('Phrity\Pdo\Query\DescSort', $desc);
        $this->assertSame('(`tn`.`field_name`=\'my string\') DESC', $desc->define());

        $limit = $b->limit(10);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 10', $limit->define());

        $limit = $b->limit(10);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 10', $limit->define());

        $limit = $b->limit(10, 5);
        $this->assertInstanceOf('Phrity\Pdo\Query\Limit', $limit);
        $this->assertSame('LIMIT 5,10', $limit->define());
    }

    public function quoting(): void
    {
        $b = new Builder($this->getPdo());

        $this->assertSame("'My string'", $b->q('My string'));
        $this->assertSame(1234, $b->q(1234));
        $this->assertSame(12.34, $b->q(12.34));
        $this->assertSame(1, $b->q(true));
        $this->assertSame('null', $b->q(null));
        $this->assertSame([3], $b->q([3]));
        $this->assertSame("`My string`", $b->escape('My string'));
    }
}
