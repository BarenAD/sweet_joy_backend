<?php

namespace App\Exceptions;

use Exception;

class InsufficientAdministrationRights extends Exception
{
    public function render($request, Exception $exception)
    {
        return response('Недостаточно прав администрирования', 403);
    }
}
