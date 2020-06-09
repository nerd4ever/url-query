<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaStart implements ICriteria
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
     * @return CriteriaStart
     */
    public function setValue($value)
    {
        $this->value = $value;
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
        // TODO: Implement parser() method.
    }
}