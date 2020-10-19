<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @SWG\Tag(name="Users")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the informations of an user",
     *     @SWG\Schema(
     *         type="array",
     *         example={"username": "John Doe","email": "my@email.com", "password": "MyPassword", "client": "ID"},
     *         @SWG\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param string $id
     * @return Response
     */
    public function showAction(UserRepository $userRepository, SerializerInterface $serializer, string $id): Response
    {
        $user = $userRepository->findBy(['id' => $id]);

        $data = $serializer->serialize($user, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/user/", name="user_list")
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function usersListAction(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $user = $userRepository->findAll();
        $data = $serializer->serialize($user, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
