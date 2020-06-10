<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaGreaterThan implements ICriteria
{
    private $value;
    private $field;


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

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     * @return CriteriaGreaterThan
     */
    public function setField($field)
    {
        $this->field = $field;
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
        $pattern = sprintf('/^([_a-zA-Z\d]+)=(%s):(.+)$/', $this->getOperator());
        if (preg_match($pattern, $value, $matches) !== 1) return false;
        if (count($matches) != 4) return false;
        if ($this->getOperator() != $matches[2]) return false;
        $this->setField($matches[1]);
        $this->setValue($matches[3]);
        return true;
    }
}