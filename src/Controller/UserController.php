<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the informations of an user",
     *     @SWG\Schema(
     *         type="array",
     *         example={"username": "username","email": "email", "password": "password", "clients": "[]"},
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
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Add a new user",
     *     @SWG\Schema(
     *         type="array",
     *         example={"username": "username","email": "email", "password": "password"},
     *         @SWG\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @Route("/user/", name="user_list")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($user);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('User has been added successfully', Response::HTTP_CREATED);
    }

    /**
     * @SWG\Tag(name="User")
     * * @SWG\Response(
     *     response=200,
     *     description="Returns the list of users",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
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
