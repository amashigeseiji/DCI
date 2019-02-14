<?php
namespace App\Transfer;

use App\DCI\Feedback;

final class TransferSuccess extends Feedback
{
    public function __toString()
    {
        return 'success';
    }
}
