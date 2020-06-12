# nerd4ever/url-query

A library to extract parameters to filter and sort.

[![codecov](https://codecov.io/gh/nerd4ever/url-query/branch/master/graph/badge.svg)](https://codecov.io/gh/nerd4ever/url-query)
[![GitHub license](https://img.shields.io/github/license/nerd4ever/url-query)](https://github.com/nerd4ever/url-query/blob/master/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/nerd4ever/url-query)](https://github.com/nerd4ever/url-query/issues)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D_7.1.3-8892BF.svg)](https://github.com/symfony/symfony)

## Installation

```bash
composer require nerd4ever/url-query
```

## Supported operators
Operators used in filters and your syntax
 
- **ge** value greater than or equals
> ge.: *test=between:3,5* (**field** => test, **operator** => between, start => 3, end => 5 )
- **le** value less than or equals
> eg.: *test=le:3* (**field** => test, **operator** => le, value => 3)
- **ne** value not equals
> eg.: *test=ne:3* (**field** => test, **operator** => ne, value => 3)
- **eq** value equals
> eg.: *test=eq:3* (**field** => test, **operator** => eq, value => 3)
- **gt** value greater than
> eg.: *test=gt:3* (**field** => test, **operator** => gt, value => 3)
- **lt** value less than
> eg.: *test=lt:5* (**field** => test, **operator** => lt, value => 5)
- **regex** value validate by regular expression
> eg.: *test=regex:[0-9]* (field => test, operator => regex, values => [0-9])
- **in** one or more possible values (separated by commas)
> eg.: *test=in:3,4,5* (**field** => test, **operator** => in, values => [3,4,5])
- **between** values between start and end range (separated by commas).
> eg.: *test=between:3,5* (**field** => test, **operator** => between, start => 3, end => 5 )
- **contains** value anywhere
> eg.: *test=contains:3* (**field** => test, **operator** => contains, value => 3)
- **start** value at the beginning
> eg.: *test=start:3* (**field** => test, **operator** => start, value => 3)
- **finish** value at the end
> eg.: *test=finish:3* (**field** => test, **operator** => finish, value => 3)
- **nil** value null
> eg.: *test=nil:* (**field** => test, **operator** => nil)

## Sorter syntax
```
http://sandbox.nerd4ever.com.br/url-query?_orders=data1:asc&data2:desc&data3
```
- *test:asc* (**field** => test, **type**=> asc)
- *test:desc* (**field** => test, **type**=> desc)
- *test* (**field** => test, **type**=> asc)

## Limit syntax
```
http://sandbox.nerd4ever.com.br/url-query?_limit=10
```
- *_limit=10* (**property** => _limit, **value**=> 10)

## Offset syntax
```
http://sandbox.nerd4ever.com.br/url-query?_limt=10&_offset=1
```

- *_limit=10* (**property** => _limit, **value**=> 10)
- *_offset=1* (**property** => __offset, **value**=> 1)

## QueryString example
```
http://sandbox.nerd4ever.com.br/url-query?data0=3&data1=ge:3&data2=le:3&data3=ne:3&data4=eq:3&data5=gt:3&data6=lt:3&data7=regex:[0-9]&data8=in:3,4,5&data9=between:3,5&data10=contains:3&data11=start:3&data12=finish:5&data13=nil:&_orders=data1:asc,data2:desc,data3
```
## Usage

```php
use Nerd4ever\UrlQuery\Model\UrlQuery;

$urlQuery = new UrlQuery();
$urlQuery->parser($_SERVER['QUERY_STRING']);
```

> Output example
> For var_export($urlQuery, true) from code above, using the QUERY_STRING
> ?data0=3&data1=ge:3&data2=le:3&data3=ne:3&data4=eq:3&data5=gt:3&data6=lt:3&data7=regex:[0-9]&data8=in:3,4,5&data9=between:3,5&data10=contains:3&data11=start:3&data12=finish:5&data13=nil:&_orders=data1:asc,data2:desc,data3
```php
Nerd4ever\UrlQuery\Model\UrlQuery::__set_state(array(
   'reservedSortField' => '_orders',
   'filters' => 
  array (
    0 => 
    Nerd4ever\UrlQuery\Model\CriteriaEquals::__set_state(array(
       'value' => '3',
       'field' => 'data0',
    )),
    1 => 
    Nerd4ever\UrlQuery\Model\CriteriaGreaterThanOrEquals::__set_state(array(
       'value' => '3',
       'field' => 'data1',
    )),
    2 => 
    Nerd4ever\UrlQuery\Model\CriteriaLessThanOrEquals::__set_state(array(
       'value' => '3',
       'field' => 'data2',
    )),
    3 => 
    Nerd4ever\UrlQuery\Model\CriteriaNotEquals::__set_state(array(
       'field' => 'data3',
       'value' => '3',
    )),
    4 => 
    Nerd4ever\UrlQuery\Model\CriteriaEquals::__set_state(array(
       'value' => '3',
       'field' => 'data4',
    )),
    5 => 
    Nerd4ever\UrlQuery\Model\CriteriaGreaterThan::__set_state(array(
       'value' => '3',
       'field' => 'data5',
    )),
    6 => 
    Nerd4ever\UrlQuery\Model\CriteriaLessThan::__set_state(array(
       'value' => '3',
       'field' => 'data6',
    )),
    7 => 
    Nerd4ever\UrlQuery\Model\CriteriaRegex::__set_state(array(
       'value' => '[0-9]',
       'field' => 'data7',
    )),
    8 => 
    Nerd4ever\UrlQuery\Model\CriteriaIn::__set_state(array(
       'values' => 
      array (
        0 => '3',
        1 => '4',
        2 => '5',
      ),
       'field' => 'data8',
    )),
    9 => 
    Nerd4ever\UrlQuery\Model\CriteriaBetween::__set_state(array(
       'start' => '3',
       'end' => '5',
       'field' => 'data9',
    )),
    10 => 
    Nerd4ever\UrlQuery\Model\CriteriaContains::__set_state(array(
       'value' => '3',
       'field' => 'data10',
    )),
    11 => 
    Nerd4ever\UrlQuery\Model\CriteriaStart::__set_state(array(
       'value' => '3',
       'field' => 'data11',
    )),
    12 => 
    Nerd4ever\UrlQuery\Model\CriteriaFinish::__set_state(array(
       'value' => '5',
       'field' => 'data12',
    )),
    13 => 
    Nerd4ever\UrlQuery\Model\CriteriaNil::__set_state(array(
       'field' => 'data13',
    )),
  ),
   'sorters' => 
  array (
    0 => 
    Nerd4ever\UrlQuery\Model\Sorter::__set_state(array(
       'type' => 'asc',
       'field' => 'data1',
    )),
    1 => 
    Nerd4ever\UrlQuery\Model\Sorter::__set_state(array(
       'type' => 'desc',
       'field' => 'data2',
    )),
    2 => 
    Nerd4ever\UrlQuery\Model\Sorter::__set_state(array(
       'type' => 'asc',
       'field' => 'data3',
    )),
  ),
));
```
[Nerd4ever Official Home](http://www.nerd4ever.com.br)
