<?php

declare(strict_types=1);

namespace App\Domain\Main\DirectMessage;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DirectMessageNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The DirectMessage you requested does not exist.';
}
