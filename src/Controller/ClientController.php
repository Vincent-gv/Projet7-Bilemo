<?php


namespace App\Controller;


use App\Entity\Client;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the informations of a client",
     *     @SWG\Schema(
     *         type="array",
     *         example={"first name": "first name","lastname": "lastname","email": "email", "password": "password", "user": "[]"},
     *         @SWG\Items(ref=@Model(type=Client::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
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
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=200,
     *     description="Post a new client",
     *     @SWG\Schema(
     *         type="array",
     *         example={"first name": "first name","lastname": "lastname","email": "email", "password": "password"},
     *         @SWG\Items(ref=@Model(type=Client::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @Route("/client-post/", name="client_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer)
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @SWG\Tag(name="Client")
     * * @SWG\Response(
     *     response=200,
     *     description="Returns the list of clients",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Client::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
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
