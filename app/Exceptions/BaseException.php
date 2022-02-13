<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class BaseException extends Exception
{
    private $dataToLog = [];
    private $errorHttpCode;
    private $errorMessage;
    private $errorId;

    public function __construct(string $errorName, array $dataToLog = null, ?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        $error = config("exceptions.${errorName}");

        $this->errorId = $error['id'];
        $this->errorMessage = $message ?? $error['message'];
        $this->errorHttpCode = $code ?? $error['http_code'];
        $this->dataToLog['base'] = [
            'id' => $this->errorId,
            'message' => $this->errorMessage,
        ];
        $this->dataToLog['additional'] = $dataToLog;

        parent::__construct($this->errorMessage, $this->errorHttpCode, $previous);
    }

    public function render()
    {
        return response([
            'id' => $this->errorId,
            'message' => $this->errorMessage,
        ], $this->errorHttpCode);
    }

    public function report()
    {
        Log::error($this->getMessage(), ['data' => $this->dataToLog]);
    }
}
