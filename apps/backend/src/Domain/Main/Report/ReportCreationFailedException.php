<?php

declare(strict_types=1);

namespace App\Domain\Main\Report;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ReportCreationException extends DomainRecordNotFoundException
{
    public $message = 'Failed to create the report. Please check the input data and try again.';
}
