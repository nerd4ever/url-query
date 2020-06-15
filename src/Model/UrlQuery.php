<?php


namespace Nerd4ever\UrlQuery\Model;


class UrlQuery
{
    private $reservedSortField;
    private $reservedLimitField;
    private $reservedOffsetField;
    private $filters;
    private $sorters;
    /**
     * @var null | integer
     */
    private $limit;
    /**
     * @var null | integer
     */
    private $offset;

    /**
     * UrlQuery constructor.
     * @param $reservedSortField
     * @param $reservedLimitField
     * @param $reservedOffsetField
     */
    public function __construct($reservedSortField = '_orders', $reservedLimitField = '_limit', $reservedOffsetField = '_offset')
    {
        $this->reservedSortField = $reservedSortField;
        $this->reservedLimitField = $reservedLimitField;
        $this->reservedOffsetField = $reservedOffsetField;
        $this->sorters = [];
        $this->filters = [];
        $this->limit = null;
        $this->offset = null;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    final public function getReservedLimitField(): string
    {
        return $this->reservedLimitField;
    }

    /**
     * @return string
     */
    final public function getReservedOffsetField(): string
    {
        return $this->reservedOffsetField;
    }

    /**
     * @return string
     */
    final public function getReservedSortField(): string
    {
        return $this->reservedSortField;
    }

    /**
     * @return array
     */
    public function getSorters(): array
    {
        return $this->sorters;
    }

    public function hasFilter($filter): bool
    {
        return isset($this->filters[$filter]);
    }

    public function getFilter($filter): ?ICriteria
    {
        if (!$this->hasFilter($filter)) return null;
        if (!$this->filters[$filter] instanceof ICriteria) return null;
        return $this->filters[$filter];
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    public function parser($queryString)
    {
        $mQuery = explode('?', $queryString);
        $sQuery = count($mQuery) == 2 ? $mQuery[1] : $mQuery[0];
        parse_str($sQuery, $mParameters);
        $this->filters = $this->parseFilter($mParameters);
        $this->sorters = $this->parseSort($mParameters);
        $this->offset = (isset($mParameters[$this->reservedOffsetField])) ? $mParameters[$this->reservedOffsetField] : null;
        $this->limit = (isset($mParameters[$this->reservedLimitField])) ? $mParameters[$this->reservedLimitField] : null;
    }

    private function parseSort($mData)
    {
        foreach ($mData as $mField => $mRow) {
            if ($mField != $this->reservedSortField) continue;
            $elements = explode(',', $mRow);
            $out = [];
            foreach ($elements as $mItem) {
                $s = new Sorter();
                if (!$s->parser($mItem)) return [];
                $out[] = $s;
            }
            return $out;
        }
        return [];
    }

    private function parseFilter($mData)
    {
        $out = array();
        foreach ($mData as $field => $row) {
            if (in_array($field, [$this->reservedSortField, $this->reservedLimitField, $this->reservedOffsetField])) continue;
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

            $out[$criteria->getField()] = $criteria;
        }
        return $out;
    }

    private function tryDiscoveryOperator($value)
    {
        $pattern = sprintf('/^[a-zA-Z0-9]+=([a-zA-Z0-9]+):{0,1}?.*$/');
        if (preg_match($pattern, $value, $matches) !== 1) return null;
        if (count($matches) != 2) return null;
        return (!Operators::isValid($matches[1])) ? null : $matches[1];
    }
}