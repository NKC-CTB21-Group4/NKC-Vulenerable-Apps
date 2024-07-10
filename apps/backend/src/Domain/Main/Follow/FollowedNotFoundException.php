<?php

declare(strict_types=1);

namespace App\Domain\Main\Follow;

use App\Domain\DomainException\DomainRecordNotFoundException;

class FollwedNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Follwed you requested does not exist.';
}