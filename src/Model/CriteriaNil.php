<?php


namespace Nerd4ever\UrlQuery\Model;


class CriteriaNil implements ICriteria
{

    public function getOperator()
    {
        return Operators::nil;
    }

    public function check($value)
    {
        return is_null($value);
    }

    public function parser($value)
    {
        // TODO: Implement parser() method.
    }
}