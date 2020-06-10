<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaNil implements ICriteria
{
    private $field;

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     * @return CriteriaNil
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }


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
        $pattern = sprintf('/^([_a-zA-Z\d]+)=(%s):$/', $this->getOperator());
        if (preg_match($pattern, $value, $matches) !== 1) return false;
        if (count($matches) != 3) return false;
        if ($this->getOperator() != $matches[2]) return false;
        $this->setField($matches[1]);
        return true;
    }
}