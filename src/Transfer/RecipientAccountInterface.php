<?php
namespace App\Transfer;

use App\Transfer\Currency\Currency;

interface RecipientAccountInterface
{
    public function increaceBalance(Currency $currency);
}
