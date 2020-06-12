<?php


namespace Nerd4ever\UrlQuery\Sql;

use Nerd4ever\UrlQuery\Exception\UrlQuerySqlException;
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
use Nerd4ever\UrlQuery\Model\Sorter;

abstract class AbstractUrlQuerySql implements IUrlQuerySql
{

    abstract public function urlQueryFilterBetweenToSql($field, $start, $end): ?string;

    abstract public function urlQueryFilterContainsToSql($field, $value): ?string;

    abstract public function urlQueryFilterEqualsToSql($field, $value): ?string;

    abstract public function urlQueryFilterFinishToSql($field, $value): ?string;

    abstract public function urlQueryFilterGreaterThanToSql($field, $value): ?string;

    abstract public function urlQueryFilterGreaterThanOrEqualsToSql($field, $value): ?string;

    abstract public function urlQueryFilterInToSql($field, array $values): ?string;

    abstract public function urlQueryFilterLessThanToSql($field, $value): ?string;

    abstract public function urlQueryFilterLessThanOrEqualsToSql($field, $value): ?string;

    abstract public function urlQueryFilterNilToSql($field): ?string;

    abstract public function urlQueryFilterNotEqualsToSql($field, $value): ?string;

    abstract public function urlQueryFilterRegexToSql($field, $value): ?string;

    abstract public function urlQueryFilterStartToSql($field, $value): ?string;

    abstract public function urlQuerySort($field, $type): ?string;

    /**
     * @param ICriteria $criteria
     * @param array $alias
     * @return string|null
     * @throws UrlQuerySqlException
     */
    public function urlQueryFilterToSql(ICriteria $criteria, array $alias = []): ?string
    {
        $mField = isset($alias[$criteria->getField()]) ? $alias[$criteria->getField()] : $criteria->getField();
        if ($criteria instanceof CriteriaBetween) {
            return $this->urlQueryFilterBetweenToSql($mField, $criteria->getStart(), $criteria->getEnd());
        }
        if ($criteria instanceof CriteriaContains) {
            return $this->urlQueryFilterContainsToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaEquals) {
            return $this->urlQueryFilterEqualsToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaFinish) {
            return $this->urlQueryFilterFinishToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaGreaterThan) {
            return $this->urlQueryFilterFinishToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaGreaterThanOrEquals) {
            return $this->urlQueryFilterGreaterThanOrEqualsToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaIn) {
            return $this->urlQueryFilterGreaterThanOrEqualsToSql($mField, $criteria->getValues());
        }
        if ($criteria instanceof CriteriaLessThan) {
            return $this->urlQueryFilterGreaterThanOrEqualsToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaLessThanOrEquals) {
            return $this->urlQueryFilterLessThanOrEqualsToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaNil) {
            return $this->urlQueryFilterNilToSql($mField);
        }
        if ($criteria instanceof CriteriaNotEquals) {
            return $this->urlQueryFilterNotEqualsToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaRegex) {
            return $this->urlQueryFilterRegexToSql($mField, $criteria->getValue());
        }
        if ($criteria instanceof CriteriaStart) {
            return $this->urlQueryFilterStartToSql($mField, $criteria->getValue());
        }
        throw new UrlQuerySqlException('Unknown criteria type', 106);
    }

    public function urlQuerySortToSql(Sorter $sorter, array $alias = [])
    {
        $mField = isset($alias[$sorter->getField()]) ? $alias[$sorter->getField()] : $sorter->getField();
        return $this->urlQuerySort($mField, $sorter->getType());
    }

}