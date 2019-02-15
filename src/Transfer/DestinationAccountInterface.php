<?php
namespace App\Transfer;

use App\Transfer\Currency\Currency;

interface DestinationAccountInterface
{
    public function increaceBalance(Currency $currency);
}
