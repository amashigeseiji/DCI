<?php
namespace App\Transfer;

use App\Transfer\Currency\Currency;

trait SourceAccountTrait
{
    private function beginTransaction()
    {
    }

    private function endTransaction()
    {
    }

    public function availableBalance(Currency $currency)
    {
    }

    abstract protected function decreaseBalance(Currency $currency);

    public function transferTo(RecipientAccountInterface $recipient, Currency $currency)
    {
        $this->beginTransaction();
        try {
            $this->decreaseBalance($currency);
            $recipient->increaceBalance($currency);
        } catch (\Exception $e) {
            $this->endTransaction();
            throw $e;
        }
    }
}
