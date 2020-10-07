<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ClientRepository;
use FOS\RestBundle\Controller\Annotations\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends AbstractController
{
    /**
     * @Route("/client/{id}", name="client_show", methods={"GET"})
     * @param ClientRepository $clientRepository
     * @param SerializerInterface $serializer
     * @param string $id
     * @return Response
     */
    public function showAction(ClientRepository $clientRepository, SerializerInterface $serializer, string $id): Response
    {
        $client = $clientRepository->findBy(['id' => $id]);
        $data = $serializer->serialize($client, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

     /**
     * @Route("/client-post/", name="client_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer)
    {
        $client = $serializer->deserialize($request->getContent(), Product::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/client/", name="clients_list")
     * @param ClientRepository $clientRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function ListAction(ClientRepository $clientRepository, SerializerInterface $serializer)
    {
        $client = $clientRepository->findAll();
        $data = $serializer->serialize($client, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
