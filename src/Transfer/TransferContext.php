<?php
namespace App\Transfer;

use App\DCI\Context;
use App\DCI\Feedback;
use App\Transfer\Currency\Yen;
use App\Transfer\TransferSuccess;
use App\Transfer\TransferFailed;

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

    /**
     * __construct
     *
     * @param SourceAccountInterface $source
     * @param RecipientAccountInterface $recipient
     * @return void
     */
    public function __construct(SourceAccountInterface $source, RecipientAccountInterface $recipient)
    {
        $this->source = $source;
        $this->recipient = $recipient;
    }

    /**
     * {@inheritdoc}
     *
     * Load a source account and recipient account
     */
    public static function load(): Context
    {
        return new self(
            self::make('SOURCE_ACCOUNT')->construct(new Yen(10000)),
            self::make('RECIPIENT_ACCOUNT')->construct(new Yen(10000))
        );
    }

    public function interact(): Feedback
    {
        if ($this->source->transferTo($this->recipient, new Yen(100))) {
            return new TransferSuccess;
        } else {
            return new TransferFFailed;
        }
    }
}
