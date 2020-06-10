<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaStart implements ICriteria
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
     * @return CriteriaStart
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
     * @return CriteriaStart
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }


    public function getOperator()
    {
        return Operators::start;
    }

    public function check($value)
    {
        $needle = is_string($value) ? $value : strval($value);
        $haystack = is_string($this->getValue()) ? $this->getValue() : strval($this->getValue());
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
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