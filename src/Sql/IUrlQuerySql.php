<?php


namespace Nerd4ever\UrlQuery\Sql;


use Nerd4ever\UrlQuery\Model\ICriteria;
use Nerd4ever\UrlQuery\Model\Sorter;

interface IUrlQuerySql
{
    public function urlQueryFilterToSql(ICriteria $criteria, array $alias = []);

    public function urlQuerySortToSql(Sorter $sorter, array $alias = []);

    public function urlQueryLimitToSql(?int $limit, ?int $offset);
}