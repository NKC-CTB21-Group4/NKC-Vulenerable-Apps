<?php

declare(strict_types=1)

namespace App\Domain\Main\Follow;

use App\Domain\DomainException\DomainRecordNotFoundException;

class FollwerDeleteFailedException extends DomainRecordNotFoundException
{
    public $message = 'Follwer Delete failed';
}