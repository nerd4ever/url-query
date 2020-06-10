<?php


namespace Nerd4ever\UrlQuery\Model;


class Operators
{
    const ge = 'ge'; // value greater than or equals
    const le = 'le'; // value less than or equals
    const ne = 'ne'; // value not equals
    const eq = 'eq'; // value equals
    const gt = 'gt'; // value greater than
    const lt = 'lt'; // value less than
    const regex = 'regex'; // value validate by regular expression
    const in = 'in'; // one or more possible values (separated by commas)
    const between = 'between'; // values between minimum and maximum range (separated by commas)
    const contains = 'contains'; // value anywhere
    const start = 'start'; // value at the beginning
    const finish = 'finish'; // value at the end
    const nil = 'nil'; // value null

    /**
     * Check if operator is valid
     * @param $value
     * @return bool
     */
    public static function isValid($value)
    {
        return in_array($value, Operators::list());
    }

    public static function list()
    {
        return [
            Operators::ge,
            Operators::le,
            Operators::ne,
            Operators::eq,
            Operators::gt,
            Operators::lt,
            Operators::regex,
            Operators::in,
            Operators::between,
            Operators::contains,
            Operators::start,
            Operators::finish,
            Operators::nil
        ];
    }
}