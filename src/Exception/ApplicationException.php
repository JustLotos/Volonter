<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

abstract class ApplicationException extends HttpException
{
    public function __construct(string $message, int $code, ?Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}
