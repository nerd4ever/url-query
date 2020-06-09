<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaEquals implements ICriteria
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
     * @return CriteriaEquals
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getOperator()
    {
        return Operators::eq;
    }

    public function check($value)
    {
        return $this->getValue() == $value;
    }

    public function parser($value)
    {
        // TODO: Implement parser() method.
    }
}