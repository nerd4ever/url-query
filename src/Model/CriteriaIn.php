<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaIn implements ICriteria
{
    /**
     * @var array
     */
    private $values;
    private $field;

    /**
     * CriteriaIn constructor.
     */
    public function __construct()
    {
        $this->values = [];
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return CriteriaIn
     */
    public function setValues(array $values): CriteriaIn
    {
        $this->values = $values;
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
     * @return CriteriaIn
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    public function append($value)
    {
        $this->values[] = $value;
    }

    public function drop($value)
    {
        if (($key = array_search($value, $this->values)) !== false) {
            unset($this->values[$key]);
        }
    }

    public function getOperator()
    {
        return Operators::in;
    }

    public function check($value)
    {
        return in_array($value, $this->getValues());
    }

    public function parser($value)
    {
        $pattern = sprintf('/^([_a-zA-Z\d]+)=(%s):(.+)$/', $this->getOperator());
        if (preg_match($pattern, $value, $matches) !== 1) return false;
        if (count($matches) != 4) return false;
        if ($this->getOperator() != $matches[2]) return false;
        $this->setField($matches[1]);
        $mData = explode(',', $matches[3]);
        $this->setValues($mData);
        return true;
    }
}