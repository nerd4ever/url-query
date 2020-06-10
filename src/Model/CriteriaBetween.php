<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaBetween implements ICriteria
{
    private $start;
    private $end;
    private $field;

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     * @return CriteriaBetween
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     * @return CriteriaBetween
     */
    public function setEnd($end)
    {
        $this->end = $end;
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
     * @return CriteriaBetween
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }


    public function getOperator()
    {
        return Operators::between;
    }

    public function check($value)
    {
        return $value >= $this->getStart() && $value <= $this->getEnd();
    }

    public function parser($value)
    {
        $pattern = sprintf('/^([_a-zA-Z\d]+)=(%s):(.+)?,(.+)$/', $this->getOperator());
        if (preg_match($pattern, $value, $matches) !== 1) return false;
        if (count($matches) != 5) return false;
        if ($this->getOperator() != $matches[2]) return false;
        $this->setStart($matches[3]);
        $this->setEnd($matches[4]);
        $this->setField($matches[1]);
        return true;
    }


}