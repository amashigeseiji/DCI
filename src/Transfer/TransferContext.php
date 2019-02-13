<?php
namespace App\Transfer;

use App\DCI\Context;
use App\Transfer\Currency\Currency;

final class TransferContext extends Context
{
    const SOURCE_ACCOUNT = [
        Account::class,
        [SourceAccountInterface::class],
        [SourceAccountTrait::class]
    ];

    const RECIPIENT_ACCOUNT = [
        Account::class,
        [RecipientAccountInterface::class],
        [RecipientAccountTrait::class]
    ];
}
