<?php
namespace App\Transfer;

use App\DCI\Interaction;
use App\Transfer\Currency\Yen;

final class TransferInteraction extends Interaction
{
    public function doIt()
    {
        $feedback = TransferContext::load()->interact();
        echo $feedback;
        //$source = TransferContext::make('SOURCE_ACCOUNT')->construct(new Yen(10000));
        //$recipient = TransferContext::make("RECIPIENT_ACCOUNT")->construct(new Yen(100));
        //$source->transferTo($recipient, new Yen(10000));


        //$source2 = TransferContext::make('SOURCE_ACCOUNT')->construct(new Yen(10000));
        //$recipient2 = TransferContext::make('RECIPIENT_ACCOUNT')->construct(new Yen(10));

        //$sourceClass = get_class($source);
        //$sourceClass2 = get_class($source2);
        //var_dump($sourceClass, $sourceClass2);
        //var_dump(
        //    $source instanceof $sourceClass2
        //);

        //$recipientClass = get_class($recipient);
        //$recipientClass2 = get_class($recipient2);
        //var_dump($recipientClass, $recipientClass2);
        //var_dump(
        //    $recipient instanceof $recipientClass2,
        //    $source instanceof $recipientClass2
        //);
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
