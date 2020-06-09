<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaGreaterThan implements ICriteria
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
     * @return CriteriaGreaterThan
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


    public function getOperator()
    {
        return Operators::gt;
    }

    public function check($value)
    {
        if (is_numeric($this->getValue()) && is_numeric($value)) {
            return intval($value) > intval($this->getValue());
        }
        return strcasecmp(strval($value), strval($this->getValue())) > 0;
    }

    public function parser($value)
    {
        // TODO: Implement parser() method.
    }
}