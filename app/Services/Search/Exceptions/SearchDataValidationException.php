<?php

namespace App\Services\Search\Exceptions;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class SearchDataValidationException extends InvalidArgumentException
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;
}
