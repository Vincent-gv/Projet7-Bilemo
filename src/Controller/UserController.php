<?php


namespace App\Controller;


use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @Route("/client/{name}", name="client_show", methods={"GET"})
     * @param ClientRepository $clientRepository
     * @param SerializerInterface $serializer
     * @param string $name
     * @return Response
     */
    public function showAction(ClientRepository $clientRepository, SerializerInterface $serializer, string $name): Response
    {
        $client = $clientRepository->findBy(['username' => $name]);
        $data = $serializer->serialize($client, 'json');

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
