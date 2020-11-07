<?php


namespace App\Controller;


use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AController extends AbstractController
{
    /**
     * @var Serializer
     */
    private $jmsSerializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->jmsSerializer = $serializer;
    }

    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        if ($this->jmsSerializer) {
            $json = $this->jmsSerializer->serialize($data, 'json');

            return new JsonResponse($json, $status, $headers, true);
        }

        return parent::json($data, $status, $headers, $context);
    }
}