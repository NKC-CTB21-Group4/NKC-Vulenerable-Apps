<?php

declare(strict_types=1);

namespace App\Domain\Main\DirectMessage;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DirectMessageCreationException extends DomainRecordNotFoundException
{
    public $message = 'Failed to create the direct message. Please check the input data and try again.';
}
