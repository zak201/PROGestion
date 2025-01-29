<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehiculeNotFoundException extends NotFoundHttpException
{
    public function __construct(string $message = 'Véhicule non trouvé', \Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, $previous, $code);
    }
} 