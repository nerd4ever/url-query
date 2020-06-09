<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaRegex implements ICriteria
{
    private $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return CriteriaRegex
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getOperator()
    {
        return Operators::regex;
    }

    public function check($value)
    {
        $pattern = '/' . $this->getValue() . '/';
        return preg_match($pattern, $value) === 1;
    }

    public function parser($value)
    {
        // TODO: Implement parser() method.
    }
}