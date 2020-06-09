<?php


namespace Nerd4ever\UrlQuery\Tests;

use Nerd4ever\UrlQuery\Model\CriteriaBetween;
use Nerd4ever\UrlQuery\Model\CriteriaContains;
use Nerd4ever\UrlQuery\Model\CriteriaEquals;
use Nerd4ever\UrlQuery\Model\CriteriaFinish;
use Nerd4ever\UrlQuery\Model\CriteriaStart;
use Nerd4ever\UrlQuery\Model\ICriteria;
use Nerd4ever\UrlQuery\Model\Operators;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @dataProvider operatorsProvider
     * @param $operator
     * @param $expected
     */
    public function testOperators($operator, $expected)
    {
        $this->assertSame($expected, Operators::isValid($operator));
    }

    /**
     * @dataProvider criteriaProvider
     * @param $criteria
     * @param $value
     * @param $expected
     */
    public function testCriteria(ICriteria $criteria, $value, $expected)
    {
        $this->assertSame($expected, $criteria->check($value));
    }

    public function criteriaProvider()
    {
        return [
            'criteria between out of range min' => [(new CriteriaBetween())->setStart(3)->setEnd(5), 2, false],
            'criteria between min' => [(new CriteriaBetween())->setStart(3)->setEnd(5), 3, true],
            'criteria between half' => [(new CriteriaBetween())->setStart(3)->setEnd(5), 4, true],
            'criteria between max' => [(new CriteriaBetween())->setStart(3)->setEnd(5), 5, true],
            'criteria between out of range max' => [(new CriteriaBetween())->setStart(3)->setEnd(5), 6, false],
            'criteria contains found' => [(new CriteriaContains())->setValue('nerd4ever'), 'nerd4ever.com.br', true],
            'criteria contains not found long' => [(new CriteriaContains())->setValue('nerd4ever.com.br'), 'nerd4ever', false],
            'criteria contains not found' => [(new CriteriaContains())->setValue('sileno'), 'nerd4ever.com.br', false],
            'criteria contains found int -> str' => [(new CriteriaContains())->setValue(2), '123', true],
            'criteria contains found str -> int' => [(new CriteriaContains())->setValue('2'), 123, true],
            'criteria contains found int -> int' => [(new CriteriaContains())->setValue(2), 123, true],
            'criteria equals str -> str' => [(new CriteriaEquals())->setValue('1'), '1', true],
            'criteria equals str -> int' => [(new CriteriaEquals())->setValue('1'), 1, true],
            'criteria equals int -> str' => [(new CriteriaEquals())->setValue(1), '1', true],
            'criteria equals text' => [(new CriteriaEquals())->setValue('nerd4ever'), 'nerd4ever', true],
            'criteria equals text null' => [(new CriteriaEquals())->setValue(null), null, true],
            'criteria equals text null -> str' => [(new CriteriaEquals())->setValue(null), 'nerd4ever', false],
            'criteria equals text str -> null' => [(new CriteriaEquals())->setValue('nerd4ever'), null, false],
            'criteria equals text different' => [(new CriteriaEquals())->setValue('sileno'), 'nerd4ever', false],
            'criteria finish start' => [(new CriteriaFinish())->setValue('123'), '1', false],
            'criteria finish mid' => [(new CriteriaFinish())->setValue('123'), '2', false],
            'criteria finish end str -> str' => [(new CriteriaFinish())->setValue('123'), '3', true],
            'criteria finish end int -> str' => [(new CriteriaFinish())->setValue(123), '3', true],
            'criteria finish end str -> int' => [(new CriteriaFinish())->setValue('123'), 3, true],
            'criteria finish end int -> int' => [(new CriteriaFinish())->setValue(123), 3, true],
            'criteria start start' => [(new CriteriaStart())->setValue('123'), '1', true],
            'criteria start mid' => [(new CriteriaStart())->setValue('123'), '2', false],
            'criteria start end str -> str' => [(new CriteriaStart())->setValue('123'), '3', false],
            'criteria start end int -> str' => [(new CriteriaStart())->setValue(123), '1', true],
            'criteria start end str -> int' => [(new CriteriaStart())->setValue('123'), 1, true],
            'criteria start end int -> int' => [(new CriteriaStart())->setValue(123), 1, true],
        ];
    }

    public function operatorsProvider()
    {
        return [
            'value greater than or equals' => ['ge', true],
            'value less than or equals' => ['le', true],
            'value not equals' => ['ne', true],
            'value equals' => ['eq', true],
            'value greater than' => ['gt', true],
            'value less than' => ['lt', true],
            'value validate by regular expression' => ['regex', true],
            'one or more possible values (separated by commas)' => ['in', true],
            'values between minimum and maximum range (separated by commas)' => ['between', true],
            'value anywhere' => ['contains', true],
            'value at the beginning' => ['start', true],
            'value at the end' => ['finish', true],
            'value null' => ['nil', true],
            'invalid operator' => ['other', false],
        ];
    }
}
