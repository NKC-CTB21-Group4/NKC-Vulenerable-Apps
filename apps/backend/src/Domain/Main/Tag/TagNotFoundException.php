<?php

declare(strict_types=1);

namespace App\Domain\Main\Tag;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TagNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The tag you requested does not exist.';
}
