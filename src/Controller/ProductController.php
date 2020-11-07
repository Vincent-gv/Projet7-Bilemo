<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        parent::__construct($serializer);
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @SWG\Tag(name="Product")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the informations of a product",
     *     @SWG\Schema(
     *         type="array",
     *         example={"name": "name", "price": "price","description": "description"},
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"full"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when ressource is not found"
     * )
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param string $id
     * @return Response
     */
    public function showAction(ProductRepository $productRepository, string $id): Response
    {
        $product = $productRepository->findBy(['id' => $id]);
        return $this->json(
            $product,
            200,
            [],
            [
                'groups' => ['show']
            ]
        );
    }

    /**
     * @SWG\Tag(name="Product")
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
     * @Route("/product", name="product_list", methods={"GET"})
     * @return Response
     */
    public function listAction()
    {
        return $this->json(
            $this->productRepository->findAll(),
            200,
            [],
            [
                'groups' => ['list']
            ]
        );
    }
}
