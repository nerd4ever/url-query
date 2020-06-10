<?php


namespace Nerd4ever\UrlQuery\Model;


class UrlQuery
{
    private $filters;

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return $this->filters;
    }

    public function parser($queryString)
    {
        $mQuery = explode('?', $queryString);
        $sQuery = count($mQuery) == 2 ? $mQuery[1] : $mQuery[0];
        parse_str($sQuery, $mFilters);
        $this->filters = $this->parseFilter($mFilters);
    }

    private function parseFilter($mData)
    {
        $out = array();
        foreach ($mData as $field => $row) {
            $mQueryString = sprintf('%s=%s', $field, urldecode($row));
            $mOperator = $this->tryDiscoveryOperator($mQueryString);
            $criteria = null;
            switch ($mOperator) {
                case Operators::ge:
                    $criteria = new CriteriaGreaterThanOrEquals();
                    break;
                case Operators::le:
                    $criteria = new CriteriaLessThanOrEquals();
                    break;
                case Operators::ne:
                    $criteria = new CriteriaNotEquals();
                    break;
                case Operators::gt:
                    $criteria = new CriteriaGreaterThan();
                    break;
                case Operators::lt:
                    $criteria = new CriteriaLessThan();
                    break;
                case Operators::regex:
                    $criteria = new CriteriaRegex();
                    break;
                case Operators::in:
                    $criteria = new CriteriaIn();
                    break;
                case Operators::between:
                    $criteria = new CriteriaBetween();
                    break;
                case Operators::contains:
                    $criteria = new CriteriaContains();
                    break;
                case Operators::start:
                    $criteria = new CriteriaStart();
                    break;
                case Operators::finish:
                    $criteria = new CriteriaFinish();
                    break;
                case Operators::nil:
                    $criteria = new CriteriaNil();
                    break;
                case Operators::eq:
                default:
                    $criteria = new CriteriaEquals();
                    break;
            }
            if (!$criteria instanceof ICriteria) continue;
            if (!$criteria->parser($mQueryString)) continue;
            $out[] = $criteria;
        }
        return $out;
    }

    private function tryDiscoveryOperator($value)
    {
        $pattern = sprintf('/^[_a-zA-Z\d]+=(.+)?:.*$/');
        if (preg_match($pattern, $value, $matches) !== 1) return null;
        if (count($matches) != 2) return null;
        return (!Operators::isValid($matches[1])) ? null : $matches[1];
    }
}