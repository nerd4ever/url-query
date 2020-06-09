<?php


namespace Nerd4ever\UrlQuery\Model;


final class CriteriaBetween implements ICriteria
{
    private $start;
    private $end;

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
        // TODO: Implement parser() method.
    }
}