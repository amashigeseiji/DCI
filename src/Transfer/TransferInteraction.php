<?php
namespace App\Transfer;

use App\DCI\Interaction;
use App\Transfer\Currency\Yen;

final class TransferInteraction extends Interaction
{
    public function doIt()
    {
        $source = TransferContext::make('SOURCE_ACCOUNT')->construct(new Yen(10000));
        $recipient = TransferContext::make("RECIPIENT_ACCOUNT")->construct(new Yen(100));
        $source->transferTo($recipient, new Yen(10000));

        //var_dump(
        //    $recipient instanceof Account,
        //    $recipient instanceof SourceAccountInterface,
        //    $recipient instanceof RecipientAccountInterface
        //);
        //var_dump(
        //    $source instanceof Account,
        //    $source instanceof SourceAccountInterface,
        //    $source instanceof RecipientAccountInterface
        //);

    }
}
