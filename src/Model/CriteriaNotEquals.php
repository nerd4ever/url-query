<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaNotEquals implements ICriteria
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
     * @return CriteriaNotEquals
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


    public function getOperator()
    {
        return Operators::ne;
    }

    public function check($value)
    {
        return $this->getValue() != $value;
    }

    public function parser($value)
    {
        // TODO: Implement parser() method.
    }
}