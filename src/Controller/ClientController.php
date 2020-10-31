<?php


namespace App\Controller;


use App\Entity\Client;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(EntityManagerInterface $entityManager, ClientRepository $clientRepository)
    {
        $this->entityManager = $entityManager;
        $this->clientRepository = $clientRepository;
    }

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
     * @param string $id
     * @return Response
     */
    public function showAction(ClientRepository $clientRepository,string $id): Response
    {
        $client = $clientRepository->findBy(['id' => $id]);
        return $this->json(
            $client,
            200,
            [],
            [
                'groups' => ['show']
            ]
        );
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
     * @Route("/client", name="client_create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $book = new Client();

        $form = $this->createForm(ClientFormType::class, $book);

        $requestData = json_decode($request->getContent(), true);

        $form->submit($requestData);

        if (!($form->isSubmitted() && $form->isValid())) {
            return new JsonResponse([], 400);
        }

        persist($book);
        $this->entityManager->flush();

        return $this->json(
            $book,
            200,
            [],
            [
                'groups' => ['show', 'list']
            ]
        );
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
     * @Route("/client", name="clients_list", methods={"GET"})
     * @return Response
     */
    public function listAction()
    {
        return $this->json(
            $this->clientRepository->findAll(),
            200,
            [],
            [
                'groups' => ['list']
            ]
        );
    }

    /**
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=200,
     *     description="Delete a client",
     *     @SWG\Schema(
     *         type="array",
     *         example={"id": "id"},
     *         @SWG\Items(ref=@Model(type=Client::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @Route("/client/{id}", name="client_delete", methods={"DELETE"})
     * @param Client $client
     * @return Response
     */
    public function deleteAction(Client $client): Response
    {
        $this->entityManager->remove($client);
        $this->entityManager->flush();

        return new JsonResponse();
    }

    /**
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=200,
     *     description="Update a client",
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
     * @Route("/client/{id}", name="client_update", methods={"PUT"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function updateAction(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientFormType::class, $client);

        $requestData = json_decode($request->getContent(), true);

        $form->submit($requestData);

        if (!($form->isSubmitted() && $form->isValid())) {
            return new JsonResponse([], 400);
        }

        $this->entityManager->flush();

        return $this->json(
            $client,
            200,
            [],
            [
                'groups' => ['show', 'list']
            ]
        );
    }
}
