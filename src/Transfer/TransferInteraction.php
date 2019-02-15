<?php
namespace App\Transfer;

use App\DCI\Interaction;
use App\DCI\Input;
use App\Transfer\Currency\Yen;
use App\Transfer\TransferAction;

final class TransferInteraction extends Interaction
{
    public function doIt(Input $input)
    {
        $feedback = TransferContext::load()->interact(new TransferAction($input));
        echo $feedback;
    }
}
