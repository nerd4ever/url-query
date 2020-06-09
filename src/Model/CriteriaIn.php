<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaIn implements ICriteria
{
    /**
     * @var array
     */
    private $values;

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
        // TODO: Implement parser() method.
    }
}