<?php
namespace App\Transfer;

use App\Transfer\Currency\Currency;

class Account
{
    private $currency;

    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
    }

    protected function decreaseBalance(Currency $currency)
    {
    }

    public function increaceBalance(Currency $currency)
    {
    }
}
