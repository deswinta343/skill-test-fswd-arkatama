<?php

namespace App\Exceptions;

use Exception;

class RestrictDeleteException extends Exception
{
    public function __construct($message = null, $code = 520)
    {
        parent::__construct($message ?? 'Cannot delete this record because it has related records.', $code);
    }
}
