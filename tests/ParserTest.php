<?php


namespace Nerd4ever\UrlQuery\Tests;

use Nerd4ever\UrlQuery\Model\CriteriaBetween;
use Nerd4ever\UrlQuery\Model\CriteriaContains;
use Nerd4ever\UrlQuery\Model\CriteriaEquals;
use Nerd4ever\UrlQuery\Model\CriteriaFinish;
use Nerd4ever\UrlQuery\Model\CriteriaGreaterThan;
use Nerd4ever\UrlQuery\Model\CriteriaGreaterThanOrEquals;
use Nerd4ever\UrlQuery\Model\CriteriaIn;
use Nerd4ever\UrlQuery\Model\CriteriaLessThan;
use Nerd4ever\UrlQuery\Model\CriteriaLessThanOrEquals;
use Nerd4ever\UrlQuery\Model\CriteriaNil;
use Nerd4ever\UrlQuery\Model\CriteriaNotEquals;
use Nerd4ever\UrlQuery\Model\CriteriaRegex;
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
            'criteria great than major int -> str' => [(new CriteriaGreaterThan())->setValue(2), 'a', true],
            'criteria great than major str -> int' => [(new CriteriaGreaterThan())->setValue('a'), 2, false],
            'criteria great than major int -> int' => [(new CriteriaGreaterThan())->setValue(2), 3, true],
            'criteria great than minor int -> int' => [(new CriteriaGreaterThan())->setValue(2), 1, false],
            'criteria great than equals int -> int' => [(new CriteriaGreaterThan())->setValue(2), 2, false],
            'criteria great than major str -> str' => [(new CriteriaGreaterThan())->setValue('nerd4ever'), 'sileno', true],
            'criteria great than minor str -> str' => [(new CriteriaGreaterThan())->setValue('sileno'), 'nerd4ever', false],
            'criteria great than equals str -> str' => [(new CriteriaGreaterThan())->setValue('nerd4ever'), 'nerd4ever', false],
            'criteria less than major int -> str' => [(new CriteriaLessThan())->setValue(2), 'a', false],
            'criteria less than major str -> int' => [(new CriteriaLessThan())->setValue('a'), 2, true],
            'criteria less than major int -> int' => [(new CriteriaLessThan())->setValue(2), 3, false],
            'criteria less than minor int -> int' => [(new CriteriaLessThan())->setValue(2), 1, true],
            'criteria less than equals int -> int' => [(new CriteriaLessThan())->setValue(2), 2, false],
            'criteria less than major str -> str' => [(new CriteriaLessThan())->setValue('nerd4ever'), 'sileno', false],
            'criteria less than minor str -> str' => [(new CriteriaLessThan())->setValue('sileno'), 'nerd4ever', true],
            'criteria less than equals str -> str' => [(new CriteriaLessThan())->setValue('nerd4ever'), 'nerd4ever', false],
            'criteria not equals str -> str' => [(new CriteriaNotEquals())->setValue('1'), '1', false],
            'criteria not equals str -> int' => [(new CriteriaNotEquals())->setValue('1'), 1, false],
            'criteria not equals int -> str' => [(new CriteriaNotEquals())->setValue(1), '1', false],
            'criteria not equals text' => [(new CriteriaNotEquals())->setValue('nerd4ever'), 'nerd4ever', false],
            'criteria not equals text null' => [(new CriteriaNotEquals())->setValue(null), null, false],
            'criteria not equals text null -> str' => [(new CriteriaNotEquals())->setValue(null), 'nerd4ever', true],
            'criteria not equals text str -> null' => [(new CriteriaNotEquals())->setValue('nerd4ever'), null, true],
            'criteria not equals text different' => [(new CriteriaNotEquals())->setValue('sileno'), 'nerd4ever', true],
            'criteria nil text null' => [(new CriteriaNil()), 'nerd4ever', false],
            'criteria nil text int' => [(new CriteriaNil()), null, true],
            'criteria nil text str' => [(new CriteriaNil()), 1, false],
            'criteria nil text empty' => [(new CriteriaNil()), '', false],
            'criteria nil text zero' => [(new CriteriaNil()), '0', false],
            'criteria less than or equals major int -> str' => [(new CriteriaLessThanOrEquals())->setValue(2), 'a', false],
            'criteria less than or equals major str -> int' => [(new CriteriaLessThanOrEquals())->setValue('a'), 2, true],
            'criteria less than or equals major int -> int' => [(new CriteriaLessThanOrEquals())->setValue(2), 3, false],
            'criteria less than or equals minor int -> int' => [(new CriteriaLessThanOrEquals())->setValue(2), 1, true],
            'criteria less than or equals equals int -> int' => [(new CriteriaLessThanOrEquals())->setValue(2), 2, true],
            'criteria less than or equals major str -> str' => [(new CriteriaLessThanOrEquals())->setValue('nerd4ever'), 'sileno', false],
            'criteria less than or equals minor str -> str' => [(new CriteriaLessThanOrEquals())->setValue('sileno'), 'nerd4ever', true],
            'criteria less than or equals equals str -> str' => [(new CriteriaLessThanOrEquals())->setValue('nerd4ever'), 'nerd4ever', true],
            'criteria great than or equals major int -> str' => [(new CriteriaGreaterThanOrEquals())->setValue(2), 'a', true],
            'criteria great than or equals major str -> int' => [(new CriteriaGreaterThanOrEquals())->setValue('a'), 2, false],
            'criteria great than or equals major int -> int' => [(new CriteriaGreaterThanOrEquals())->setValue(2), 3, true],
            'criteria great than or equals minor int -> int' => [(new CriteriaGreaterThanOrEquals())->setValue(2), 1, false],
            'criteria great than or equals equals int -> int' => [(new CriteriaGreaterThanOrEquals())->setValue(2), 2, true],
            'criteria great than or equals major str -> str' => [(new CriteriaGreaterThanOrEquals())->setValue('nerd4ever'), 'sileno', true],
            'criteria great than or equals minor str -> str' => [(new CriteriaGreaterThanOrEquals())->setValue('sileno'), 'nerd4ever', false],
            'criteria great than or equals equals str -> str' => [(new CriteriaGreaterThanOrEquals())->setValue('nerd4ever'), 'nerd4ever', true],
            'criteria in found int' => [(new CriteriaIn())->setValues(['1', '2', '3']), 3, true],
            'criteria in found str' => [(new CriteriaIn())->setValues(['1', '2', '3']), "1", true],
            'criteria in not found int' => [(new CriteriaIn())->setValues(['1', '2', '3']), 4, false],
            'criteria in not found str' => [(new CriteriaIn())->setValues(['1', '2', '3']), "4", false],
            'criteria regex found' => [(new CriteriaRegex())->setValue('[0-9]'), "123", true],
            'criteria regex not found' => [(new CriteriaRegex())->setValue('[^0-9]'), "123", false],
            'criteria regex found limited' => [(new CriteriaRegex())->setValue('[0-9]{3}'), "123", true],
            'criteria regex not found limited' => [(new CriteriaRegex())->setValue('[0-9]{3}'), "1", false],
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
