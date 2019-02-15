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

    public function transferTo(DestinationAccountInterface $destination, Currency $currency): bool
    {
        $this->beginTransaction();
        try {
            $this->decreaseBalance($currency);
            $destination->increaceBalance($currency);
            return true;
        } catch (\Exception $e) {
            $this->endTransaction();
            throw $e;
        }
        return false;
    }
}
