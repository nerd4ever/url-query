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
use Nerd4ever\UrlQuery\Model\UrlQuery;
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

    public function testQueryString()
    {
        $urlQuery = new UrlQuery();
        $mFilters = [
            'data0' => sprintf('3'),
            'data1' => sprintf('%s:3', Operators::ge),
            'data2' => sprintf('%s:3', Operators::le),
            'data3' => sprintf('%s:3', Operators::ne),
            'data4' => sprintf('%s:3', Operators::eq),
            'data5' => sprintf('%s:3', Operators::gt),
            'data6' => sprintf('%s:3', Operators::lt),
            'data7' => sprintf('%s:[0-9]', Operators::regex),
            'data8' => sprintf('%s:3,4,5', Operators::in),
            'data9' => sprintf('%s:3,5', Operators::between),
            'data10' => sprintf('%s:3', Operators::contains),
            'data11' => sprintf('%s:3', Operators::start),
            'data12' => sprintf('%s:5', Operators::finish),
            'data13' => sprintf('%s:', Operators::nil),
        ];
        $i = sizeof($mFilters);

        $mSorters = ['data1:asc', 'data2:desc', 'data3'];

        $j = sizeof($mSorters);

        $urlQuery->parser(http_build_query(array_merge($mFilters, [
            $urlQuery->getReservedSortField() => join(',', $mSorters)
        ])));

        $rFilters = $urlQuery->getFilters();
        $this->assertEquals($i, sizeof($rFilters), 'query string parse filters');

        $rSorters = $urlQuery->getSorters();
        $this->assertEquals($j, sizeof($rSorters), 'query string parse sorters');


        foreach ($rFilters as $filter) {
            $this->assertTrue($filter instanceof ICriteria, sprintf('check if filter is instance of ICriteria'));
            if (!$filter instanceof ICriteria) continue;
            switch ($filter->getField()) {
                case 'data0':
                    $this->assertTrue($filter instanceof CriteriaEquals, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data1':
                    $this->assertTrue($filter instanceof CriteriaGreaterThanOrEquals, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data2':
                    $this->assertTrue($filter instanceof CriteriaLessThanOrEquals, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data3':
                    $this->assertTrue($filter instanceof CriteriaNotEquals, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data4':
                    $this->assertTrue($filter instanceof CriteriaEquals, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data5':
                    $this->assertTrue($filter instanceof CriteriaGreaterThan, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data6':
                    $this->assertTrue($filter instanceof CriteriaLessThan, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data7':
                    $this->assertTrue($filter instanceof CriteriaRegex, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data8':
                    $this->assertTrue($filter instanceof CriteriaIn, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data9':
                    $this->assertTrue($filter instanceof CriteriaBetween, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data10':
                    $this->assertTrue($filter instanceof CriteriaContains, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data11':
                    $this->assertTrue($filter instanceof CriteriaStart, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data12':
                    $this->assertTrue($filter instanceof CriteriaFinish, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                case 'data13':
                    $this->assertTrue($filter instanceof CriteriaNil, sprintf('check if %s was parser correctly', $filter->getField()));
                    break;
                default:
                    $this->fail(sprintf('field %s unexpected', $filter->getField()));
            }
        }
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

    /**
     * @dataProvider parserProvider
     * @param ICriteria $criteria
     * @param $value
     * @param $expected
     */
    public function testParser(ICriteria $criteria, $value, $expected)
    {
        $this->assertSame($expected, $criteria->parser($value));
    }

    public function parserProvider()
    {
        return [
            'parser between found' => [new CriteriaBetween(), 'date=between:3,5', true],
            'parser between invalid range' => [new CriteriaBetween(), 'date=between:3', false],
            'parser between invalid operator' => [new CriteriaBetween(), 'date=betwe:3,5', false],
            'parser between invalid field' => [new CriteriaBetween(), '=between:3,5', false],
            'parser between invalid field split' => [new CriteriaBetween(), 'datebetween:3,5', false],
            'parser contains found with operator' => [new CriteriaContains(), 'date=contains:3,5', true],
            'parser contains found without operator' => [new CriteriaContains(), 'date=contains:3', true],
            'parser contains variable alphanumeric' => [new CriteriaContains(), 'date1=contains:3', true],
            'parser contains invalid operator' => [new CriteriaContains(), 'date=3,5', false],
            'parser contains invalid field' => [new CriteriaContains(), '=contains:3,5', false],
            'parser contains invalid field split' => [new CriteriaContains(), 'datecontains:3,5', false],
            'parser equals found with operator' => [new CriteriaEquals(), 'date=eq:3,5', true],
            'parser equals found without operator' => [new CriteriaEquals(), 'date=eq:3', true],
            'parser equals variable alphanumeric' => [new CriteriaEquals(), 'date1=eq:3', true],
            'parser equals invalid operator' => [new CriteriaEquals(), 'date=3,5', true],
            'parser equals invalid operator empty' => [new CriteriaEquals(), 'date=', false],
            'parser equals invalid field' => [new CriteriaEquals(), '=contains:3,5', false],
            'parser equals invalid field split' => [new CriteriaEquals(), 'datecontains:3,5', false],
            'parser finish found with operator' => [new CriteriaFinish(), 'date=finish:3,5', true],
            'parser finish found without operator' => [new CriteriaFinish(), 'date=finish:3', true],
            'parser finish variable alphanumeric' => [new CriteriaFinish(), 'date1=finish:3', true],
            'parser finish invalid operator' => [new CriteriaFinish(), 'date=3,5', false],
            'parser finish invalid field' => [new CriteriaFinish(), '=finish:3,5', false],
            'parser finish invalid field split' => [new CriteriaFinish(), 'datefinish:3,5', false],
            'parser greater than found with operator' => [new CriteriaGreaterThan(), 'date=gt:3,5', true],
            'parser greater than found without operator' => [new CriteriaGreaterThan(), 'date=gt:3', true],
            'parser greater than variable alphanumeric' => [new CriteriaGreaterThan(), 'date1=gt:3', true],
            'parser greater than invalid operator' => [new CriteriaGreaterThan(), 'date=3,5', false],
            'parser greater than invalid field' => [new CriteriaGreaterThan(), '=gt:3,5', false],
            'parser greater than invalid field split' => [new CriteriaGreaterThan(), 'dategt:3,5', false],
            'parser greater than or equals found with operator' => [new CriteriaGreaterThanOrEquals(), 'date=ge:3,5', true],
            'parser greater than or equals found without operator' => [new CriteriaGreaterThanOrEquals(), 'date=ge:3', true],
            'parser greater than or equals variable alphanumeric' => [new CriteriaGreaterThanOrEquals(), 'date1=ge:3', true],
            'parser greater than or equals invalid operator' => [new CriteriaGreaterThanOrEquals(), 'date=3,5', false],
            'parser greater than or equals invalid field' => [new CriteriaGreaterThanOrEquals(), '=ge:3,5', false],
            'parser greater than or equals invalid field split' => [new CriteriaGreaterThanOrEquals(), 'datege:3,5', false],
            'parser less than found with operator' => [new CriteriaLessThan(), 'date=lt:3,5', true],
            'parser less than found without operator' => [new CriteriaLessThan(), 'date=lt:3', true],
            'parser less than variable alphanumeric' => [new CriteriaLessThan(), 'date1=lt:3', true],
            'parser less than invalid operator' => [new CriteriaLessThan(), 'date=3,5', false],
            'parser less than invalid field' => [new CriteriaLessThan(), '=lt:3,5', false],
            'parser less than invalid field split' => [new CriteriaLessThan(), 'datelt:3,5', false],
            'parser less than or equals found with operator' => [new CriteriaLessThanOrEquals(), 'date=le:3,5', true],
            'parser less than or equals found without operator' => [new CriteriaLessThanOrEquals(), 'date=le:3', true],
            'parser less than or equals variable alphanumeric' => [new CriteriaLessThanOrEquals(), 'date1=le:3', true],
            'parser less than or equals invalid operator' => [new CriteriaLessThanOrEquals(), 'date=3,5', false],
            'parser less than or equals invalid field' => [new CriteriaLessThanOrEquals(), '=le:3,5', false],
            'parser less than or equals invalid field split' => [new CriteriaLessThanOrEquals(), 'datele:3,5', false],
            'parser nil found with operator' => [new CriteriaNil(), 'date=nil:', true],
            'parser nil found without operator' => [new CriteriaNil(), 'date=nil:3', false],
            'parser nil variable alphanumeric' => [new CriteriaNil(), 'date1=nil:3', false],
            'parser nil invalid operator' => [new CriteriaNil(), 'date=3,5', false],
            'parser nil invalid field' => [new CriteriaNil(), '=nil:3,5', false],
            'parser nil invalid field split' => [new CriteriaNil(), 'datenil:3,5', false],
            'parser not equals found with other operator' => [new CriteriaNotEquals(), 'date=other:3,5', false],
            'parser not equals found with operator' => [new CriteriaNotEquals(), 'date=ne:3,5', true],
            'parser not equals found without operator' => [new CriteriaNotEquals(), 'date=ne:3', true],
            'parser not equals variable alphanumeric' => [new CriteriaNotEquals(), 'date1=ne:3', true],
            'parser not equals invalid operator' => [new CriteriaNotEquals(), 'date=3,5', false],
            'parser not equals invalid field' => [new CriteriaNotEquals(), '=ne:3,5', false],
            'parser not equals invalid field split' => [new CriteriaNotEquals(), 'datene:3,5', false],
            'parser regex found with other operator' => [new CriteriaRegex(), 'date=other:3,5', false],
            'parser regex found with operator' => [new CriteriaRegex(), 'date=regex:3,5', true],
            'parser regex found without operator' => [new CriteriaRegex(), 'date=regex:3', true],
            'parser regex variable alphanumeric' => [new CriteriaRegex(), 'date1=regex:3', true],
            'parser regex invalid operator' => [new CriteriaRegex(), 'regex=3,5', false],
            'parser regex invalid field' => [new CriteriaRegex(), '=regex:3,5', false],
            'parser regex invalid field split' => [new CriteriaRegex(), 'dateregex:3,5', false],
            'parser start found with other operator' => [new CriteriaStart(), 'date=other:3,5', false],
            'parser start found with operator' => [new CriteriaStart(), 'date=start:3,5', true],
            'parser start found without operator' => [new CriteriaStart(), 'date=start:3', true],
            'parser start variable alphanumeric' => [new CriteriaStart(), 'date1=start:3', true],
            'parser start invalid operator' => [new CriteriaStart(), 'start=3,5', false],
            'parser start invalid field' => [new CriteriaStart(), '=start:3,5', false],
            'parser start invalid field split' => [new CriteriaStart(), 'datestart:3,5', false],

            'parser in found with other operator' => [new CriteriaIn(), 'date=other:3,5', false],
            'parser in found with operator' => [new CriteriaIn(), 'date=in:3,5', true],
            'parser in found without operator' => [new CriteriaIn(), 'date=in:3', true],
            'parser in variable alphanumeric' => [new CriteriaIn(), 'date1=in:3', true],
            'parser in invalid operator' => [new CriteriaIn(), 'in=3,5', false],
            'parser in invalid field' => [new CriteriaIn(), '=in:3,5', false],
            'parser in invalid field split' => [new CriteriaIn(), 'datein:3,5', false],
        ];
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
