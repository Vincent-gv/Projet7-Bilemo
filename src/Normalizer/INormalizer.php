<?php


namespace App\Normalizer;


use Symfony\Component\HttpFoundation\Response;
use Throwable;

interface INormalizer
{
    public function normalize(Throwable $exception): Response;

    public function support(Throwable $exception): bool;
}