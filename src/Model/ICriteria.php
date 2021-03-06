<?php


namespace Nerd4ever\UrlQuery\Model;


interface ICriteria
{
    public function getField();

    public function parser($value);

    public function getOperator();

    public function check($value);
}