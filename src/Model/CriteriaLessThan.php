<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaLessThan implements ICriteria
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
     * @return CriteriaLessThan
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getOperator()
    {
        return Operators::lt;
    }

    public function check($value)
    {
        if (is_numeric($this->getValue()) && is_numeric($value)) {
            return intval($value) < intval($this->getValue());
        }
        return strcasecmp(strval($value), strval($this->getValue())) < 0;
    }

    public function parser($value)
    {
        // TODO: Implement parser() method.
    }
}