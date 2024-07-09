<?php

declare(strict_types=1);

namespace App\Domain\Main\Tag;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TagCreationFailedException extends DomainRecordNotFoundException
{
    public $message = 'Failed to create the tag. Please check the input data and try again.';
}
