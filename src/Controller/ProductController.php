<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AController
{
    const LIMIT = 10;
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
     *     description="Ressource is not found"
     * )
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param string $id
     * @return Response
     */
    public function showAction(ProductRepository $productRepository, string $id): Response
    {
        $product = $productRepository->findBy(['id' => $id]);

        if (!$product) {
            return $this->json([
                "status" => 404,
                "message" => "No product found"
            ],
                404);
        }

        return $this->json(
            $product,
            200,
            [],
            [
                'groups' => ['product_show']
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
     *     description="Ressource is not found"
     * )
     * @Route("/product", name="product_list", methods={"GET"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function listAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::LIMIT);

        $paginator = $this->productRepository->findAllPaginated($page, $limit);

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
                'groups' => ['product_list']
            ]
        );
    }
}
