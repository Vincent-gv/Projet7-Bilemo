<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    /**
     * @SWG\Tag(name="Products")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the informations of a product",
     *     @SWG\Schema(
     *         type="array",
     *         example={"name": "new phone","description": "phone description", "price": "800"},
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @param string $id
     * @return Response
     */
    public function showAction(ProductRepository $productRepository, SerializerInterface $serializer, string $id): Response
    {
        $product = $productRepository->findBy(['id' => $id]);
        $data = $serializer->serialize($product, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @SWG\Tag(name="Products")
     * @SWG\Response(
     *     response=200,
     *     description="Post a new phone",
     *     @SWG\Schema(
     *         type="array",
     *         example={"name": "new phone","description": "phone description", "price": "800"},
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @Route("/product-post", name="product_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json');

        $errors = $validator->validate($product);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response('Phone has been added successfully', Response::HTTP_CREATED);
    }

    /**
     * @SWG\Tag(name="Products")
     * * @SWG\Response(
     *     response=200,
     *     description="Returns the list of phones",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @Route("/product/", name="product_list")
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function listAction(ProductRepository $productRepository, SerializerInterface $serializer)
    {
        $product = $productRepository->findAll();
        $data = $serializer->serialize($product, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
