<?php
namespace App\Transfer;

use App\DCI;

final class TransferInteraction extends DCI\Interaction
{
    public function doIt(DCI\Input $input)
    {
        $feedback = TransferContext::load()->interact(new TransferAction($input));
        echo $feedback;
    }
}
