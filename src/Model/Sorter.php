<?php


namespace Nerd4ever\UrlQuery\Model;


class Sorter
{
    const asc = 'asc';
    const desc = 'desc';

    private $type;
    private $field;

    /**
     * Sorter constructor.
     */
    public function __construct()
    {
        $this->type =  Sorter::asc;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Sorter
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return Sorter
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }


    public function parser($value)
    {
        $pattern = sprintf('/^(.+)?:{0,1}(%s|%s)*$/', Sorter::asc, Sorter::desc);
        if (preg_match($pattern, $value) !== 1) return false;

        $mData = explode(':', $value, 2);
        if (count($mData) == 2 && !in_array($mData[1], [Sorter::asc, Sorter::desc])) return false;
        $this->setType(count($mData) == 2 ? $mData[1] : Sorter::asc);
        $this->setField($mData[0]);
        return true;
    }
}