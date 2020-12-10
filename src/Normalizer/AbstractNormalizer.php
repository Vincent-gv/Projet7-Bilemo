<?php


namespace App\Normalizer;


use Throwable;

abstract class AbstractNormalizer implements INormalizer
{
    public abstract function getExceptionSupported(): string;

    public function support(Throwable $exception): bool
    {
        $exceptionSupported = $this->getExceptionSupported();

        return $exception instanceof $exceptionSupported;
    }
}