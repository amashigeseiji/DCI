<?php
namespace App\Transfer;

use App\DCI;
use App\Transfer\Currency\Yen;

final class TransferContext extends DCI\Context
{
    const SOURCE_ACCOUNT = [
        Account::class,
        [SourceAccountInterface::class],
        [SourceAccountTrait::class]
    ];

    const DESTINATION_ACCOUNT = [
        Account::class,
        [DestinationAccountInterface::class],
        [DestinationAccountTrait::class]
    ];

    private $source;
    private $destination;

    /**
     * __construct
     *
     * @param SourceAccountInterface $source
     * @param DestinationAccountInterface $destination
     * @return void
     */
    public function __construct(SourceAccountInterface $source, DestinationAccountInterface $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    /**
     * {@inheritdoc}
     *
     * Load a source account and destination account
     */
    public static function load(): DCI\Context
    {
        return new self(
            self::make('SOURCE_ACCOUNT')->construct(new Yen(10000)),
            self::make('DESTINATION_ACCOUNT')->construct(new Yen(10000))
        );
    }

    public function interact(DCI\Action $action): DCI\Feedback
    {
        if ($this->source->transferTo($this->destination, new Yen(100))) {
            return new TransferSuccess;
        } else {
            return new TransferFailed;
        }
    }
}
