<?php
namespace App\DCI;

abstract class Action
{
    protected $input;

    /**
     * __construct
     *
     * @param Input $input
     * @return void
     */
    public function __construct(Input $input)
    {
        $this->input = $input;
    }
}
