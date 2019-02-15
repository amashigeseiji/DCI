<?php
namespace App\DCI;

use App\Transfer\TransferInteraction;

final class App
{
    public function run()
    {
        //todo
        $input = PHP_SAPI ? new CLIInput : Input;
        (new TransferInteraction)->doIt($input);
    }
}
