<?php
namespace App\DCI;

abstract class Interaction
{
    abstract public function doIt(Input $input);
}
