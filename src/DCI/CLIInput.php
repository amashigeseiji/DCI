<?php
namespace App\DCI;

final class CLIInput implements Input
{
    public function get()
    {
        return $_SERVER['argv'];
    }

    public function post()
    {
        return fgets(STDIN);
    }
}
