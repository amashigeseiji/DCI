<?php
namespace App\Transfer;

use App\Transfer\Currency\Currency;

interface SourceAccountInterface
{
    public function availableBalance(Currency $currency);
    public function transferTo(RecipientAccountInterface $recipient, Currency $currency): bool;
}
