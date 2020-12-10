<?php


namespace App\Controller;


use App\Entity\Client;
use App\Exception\BadFormException;
use App\Exception\BadJsonException;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientController extends AController
{
    const LIMIT = 5;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager, ClientRepository $clientRepository)
    {
        parent::__construct($serializer);
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
     *     description="Ressource is not found"
     * )
     * @Route("/client/{id}", name="client_show", methods={"GET"})
     * @param ClientRepository $clientRepository
     * @param string $id
     * @return Response
     */
    public function showAction(ClientRepository $clientRepository, string $id): Response
    {
        $client = $clientRepository->find($id);

        if (!$client) {
            return $this->json([
                "status" => 404,
                "message" => "No customer found"
            ],
                404);
        }

        return $this->json(
            $client,
            200,
            [],
            [
                'groups' => ['client_show']
            ]
        );
    }

    /**
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=201,
     *     description="Post a new client",
     *     @SWG\Schema(
     *         type="array",
     *         example={"first name": "first name","lastname": "lastname","email": "email", "password": "password"},
     *         @SWG\Items(ref=@Model(type=Client::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Ressource is not found"
     * )
     * @Route("/client", name="client_create", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws BadJsonException|BadFormException
     */
    public function createAction(Request $request): Response
    {
        $user = $this->getUser();

        $client = new Client();

        $form = $this->createForm(ClientFormType::class, $client);

        $requestData = json_decode($request->getContent(), true);

        if (!$requestData) {
            throw new BadJsonException();
        }

        $form->submit($requestData);

        if (!($form->isSubmitted() && $form->isValid())) {
            throw new BadFormException($form);
        }

        $client->setUser($user);
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $this->json(
            $client,
            201,
            [],
            [
                'groups' => ['client_show', 'client_list']
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
     *     description="Ressource is not found"
     * )
     * @Route("/client", name="client_list", methods={"GET"})
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     * @throws Exception
     */
    public function listAction(Request $request, UserInterface $user)
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::LIMIT);
        $paginator = $this->clientRepository->findPaginatedBy(['user'=>$user], $page, $limit);
        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation($paginator->getIterator()),
            'client_list',
            array(),
            $page,
            $limit,
            count($paginator) / self::LIMIT,
            'page',
            'limit',
            false,
            count($paginator)
        );

        return $this->json(
            $paginatedCollection,
            200,
            [],
            [
                'groups' => ['client_list']
            ]
        );
    }

    /**
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=204,
     *     description="Client deleted"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Ressource is not found"
     * )
     * @Route("/client/{id}", name="client_delete", methods={"DELETE"})
     * @param Client $client
     * @return Response
     */
    public function deleteAction(Client $client): Response
    {
        $this->entityManager->remove($client);
        $this->entityManager->flush();

        return new JsonResponse(null, 204);
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
     *     description="Ressource is not found"
     * )
     * @Route("/client/{id}", name="client_update", methods={"PUT"})
     * @param Request $request
     * @param Client $client
     * @return Response
     * @throws BadJsonException|BadFormException
     */
    public function updateAction(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientFormType::class, $client);

        $requestData = json_decode($request->getContent(), true);

        if (!$requestData) {
            throw new BadJsonException();
        }

        $form->submit($requestData);

        if (!($form->isSubmitted() && $form->isValid())) {
            throw new BadFormException($form);
        }

        $this->entityManager->flush();

        return $this->json(
            $client,
            200,
            [],
            [
                'groups' => ['client_show', 'client_list']
            ]
        );
    }
}
