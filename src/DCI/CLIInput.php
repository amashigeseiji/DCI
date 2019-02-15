<?php
namespace App\DCI;

final class CLIInput implements Input
{
    public function get()
    {
        return $argv;
    }

    public function post()
    {
        return fgets(STDIN);
    }
}
