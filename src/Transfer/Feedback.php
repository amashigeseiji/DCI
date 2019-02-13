<?php
namespace App\Transfer;

use App\DCI\Feedback as Base;

final class Feedback extends Base
{
    private $succeeded;

    public function __construct(bool $succeeded)
    {
        $this->succeeded = $succeeded;
    }

    public function __toString()
    {
        return $this->succeeded ? 'success': 'failed';
    }
}
