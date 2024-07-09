<?php

declare(strict_types=1);

namespace App\Domain\Main\Report;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ReportNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Report you requested does not exist.';
}
