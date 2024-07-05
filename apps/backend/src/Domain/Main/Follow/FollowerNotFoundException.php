<?php

declare(strict_types=1)

namespace App\Domain\Main\Follow;

use App\Domain\DomainException\DomainRecordNotFoundException;

class FollwerNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Follwer you requested does not exist.';
}