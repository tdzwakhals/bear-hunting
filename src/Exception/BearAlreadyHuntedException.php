<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\Bear;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BearAlreadyHuntedException extends Exception
{
    public function __construct(Bear $bear, int $code = Response::HTTP_CONFLICT, ?Throwable $previous = null)
    {
        parent::__construct("{$bear->getName()} is already hunted by you.", $code, $previous);
    }
}