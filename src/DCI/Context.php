<?php
namespace App\DCI;

abstract class Context
{
    /**
     * make
     *
     * @param string $const
     * @return ObjectCreator
     */
    public static function make(string $const): ObjectCreator
    {
        if (defined("static::{$const}")) {
            list($parent, $interfaces, $traits) = constant("static::{$const}");
            return ObjectCreator::make($parent, $interfaces, $traits);
        }
        throw new \Exception();
    }
}
