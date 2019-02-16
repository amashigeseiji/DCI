<?php
namespace App\Transfer;

use App\DCI;

final class App extends DCI\App
{
    public function run()
    {
        //todo
        (new TransferInteraction)->doIt(new DCI\CLIInput);
    }
}
