<?php
namespace App\Transfer\Currency;

abstract class Currency
{
    private $currency;

    public function __construct(int $currency)
    {
        $this->currency = $currency;
    }
}
