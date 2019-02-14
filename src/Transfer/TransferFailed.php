<?php
namespace App\Transfer;

use App\DCI\Feedback;

final class TransferFailed extends Feedback
{
    public function __toString()
    {
        return 'failed';
    }
}
