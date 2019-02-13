<?php
namespace App\Transfer;

use App\DCI\Context;
use App\DCI\Feedback;
use App\Transfer\Currency\Yen;
use App\Transfer\Feedback as TransferFeedback;

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

    private $source;
    private $recipient;

    public function __construct()
    {
        $this->source = self::make('SOURCE_ACCOUNT')->construct(new Yen(10000));
        $this->recipient = self::make('RECIPIENT_ACCOUNT')->construct(new Yen(10000));
    }

    public static function load(): Context
    {
        return new self;
    }

    public function interact(): Feedback
    {
        return new TransferFeedback($this->source->transferTo($this->recipient, new Yen(100)));
    }
}
