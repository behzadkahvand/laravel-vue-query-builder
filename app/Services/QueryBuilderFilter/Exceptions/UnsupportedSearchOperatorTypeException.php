<?php

namespace App\Services\QueryBuilderFilter\Exceptions;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class UnsupportedSearchOperatorTypeException extends InvalidArgumentException
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;
}
