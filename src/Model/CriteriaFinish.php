<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaFinish implements ICriteria
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
     * @return CriteriaFinish
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


    public function getOperator()
    {
        return Operators::finish;
    }

    public function check($value)
    {
        $needle = is_string($value) ? $value : strval($value);
        $length = strlen($needle);
        if ($length == 0) {
            return false;
        }
        $haystack = is_string($this->getValue()) ? $this->getValue() : strval($this->getValue());
        return (substr($haystack, -$length) === $needle);
    }

    public function parser($value)
    {
        // TODO: Implement parser() method.
    }
}