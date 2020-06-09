<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaContains implements ICriteria
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
     * @return CriteriaContains
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


    public function getOperator()
    {
        return Operators::contains;
    }

    public function check($value)
    {
        $a = is_string($this->getValue()) ? $this->getValue() : strval($this->getValue());
        $b = is_string($value) ? $value : strval($value);
        return strstr($b, $a) !== false;
    }

    public function parser($value)
    {
        // TODO: Implement parser() method.
    }
}