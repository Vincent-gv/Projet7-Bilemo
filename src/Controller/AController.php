<?php


namespace App\Controller;


use JMS\Serializer\SerializationContext;
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
            $serializationContext = null;
            if (count($context['groups']??[])>0) {
                $serializationContext = SerializationContext::create()->setGroups(array_merge(['Default'], $context['groups']));
            }

            $json = $this->jmsSerializer->serialize($data, 'json', $serializationContext);

            return new JsonResponse($json, $status, $headers, true);
        }

        return parent::json($data, $status, $headers, $context);
    }
}