<?php

declare(strict_types=1)

namespace App\Domain\Main\Follow;

use App\Domain\DomainException\DomainRecordNotFoundException;

class FollwerCreateFailedException extends DomainRecordNotFoundException
{
    public $message = 'Create failed';
}