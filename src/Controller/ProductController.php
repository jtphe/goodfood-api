<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Restaurant;
use App\Service\AccessControl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    private $accessControl;

    public function __construct(accessControl $accessControl)
    {
        $this->accessControl = $accessControl;
    }

    /**
     * @Route (name="selectAllProducts", path="/restaurants/{id}/products", methods={"GET"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return JsonResponse
     */
    public function selectAllProducts(Request $request, ManagerRegistry $doctrine, $id )
    {

        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findOneBy(["id" => $id]);


        if($restaurant)
        {
            $products = $em->getRepository(Product::class)->findBy(["restaurant" => [$id,null]]);
            return $this->json($products, 200);
        }

        return new JsonResponse(['message' => "Products not found"], Response::HTTP_NOT_FOUND);

    }

    /**
     * @Route (name="getProduct", path="/products/{id}", methods={"GET"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return JsonResponse
     */
    public function getProduct(Request $request, ManagerRegistry $doctrine, $id )
    {

        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);

        if($product)
        {
            return $this->json($product, 200);
        }

        return new JsonResponse(['message' => "Product not found"], Response::HTTP_NOT_FOUND);
    }


    /**
     * @Route (name="createProduct", path="/products", methods={"POST"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function createProduct(Request $request, ManagerRegistry $doctrine)
    {

        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }


        if(in_array('client', $user->getRoles(), true))
        {
            $message = ["message" => "Worker or manager access required"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }


        $productData = json_decode($request->getContent(), true);

        $em=$doctrine->getManager();

        $product = new Product();

        $product->setRestaurant($user->getRestaurant());

        $product->setName($productData['name']);
        $product->setImage($productData['image']);
        $product->setPrice($productData['price']);
        $product->setDescription($productData['description']);
        $product->setStock($productData['stock']);
        $product->setProductType($productData['productType']);


        $em->getRepository(Product::class)->add($product);
        $em->flush();

        return new JsonResponse(['message' => "Product added"], Response::HTTP_CREATED);
    }

    /**
     * @Route (name="updateProduct", path="/products/{id}", methods={"PUT"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return JsonResponse
     */
    public function updateProduct(Request $request, ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);

        if($product)
        {
            if($this->accessControl->verifyStaff($user,$product))
            {
                $message = ["message" => "Access Denied"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }

            $productData = json_decode($request->getContent(), true);

            if(isset($productData['name'])){
                $product->setName($productData['name']);
            }
            if(isset($productData['image'])){
                $product->setImage($productData['image']);
            }
            if(isset($productData['description'])){
                $product->setDescription($productData['description']);
            }
            if(isset($productData['price'])){
                $product->setDescription($productData['price']);
            }
            if(isset($productData['stock'])){
                $product->setStock($productData['stock']);
            }
            if(isset($productData['discount'])){
                $product->setDiscount($productData['discount']);
            }

            $em->persist($product);
            $em->flush();

            return new JsonResponse(['message' => "Product Updated"], Response::HTTP_CREATED);
        }
        return new JsonResponse(['message' => "Product not found"], Response::HTTP_NOT_FOUND);
    }


    /**
     * @Route (name="selectProductsByName", path="/product/byName", methods={"GET"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function selectProductsByName(Request $request, ManagerRegistry $doctrine, ProductRepository $productRepository) {

        $chars = json_decode($request->getContent(), true);
        $productsFilteredByName = $productRepository->selectProductsByName($chars);
        $arrayOfProductsFilteredByName = [];
        foreach ($productsFilteredByName as $productFilteredByName) {
            array_push($arrayOfProductsFilteredByName, $productFilteredByName);
        }
        return $this->json($arrayOfProductsFilteredByName, 200);
    }

    /**
     * @Route (name="deleteProduct", path="/products/{id}", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteProduct(Request $request, ManagerRegistry $doctrine, $id )
    {
        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);

        if($product)
        {
            if ($this->accessControl->verifyStaff($user, $product)) {
                $message = ["message" => "Access Denied"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }
        }

        $em->remove($product);
        $em->flush();

        $message = ["message" => "Product deleted"];
        return new JsonResponse($message, Response::HTTP_OK);
    }


    /**
     * @Route (name="getProductsByType", path="/restaurants/{id}/type/{type}", methods={"GET"})
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    public function getProductsByType(ManagerRegistry $doctrine, $id, $type)
    {
        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findOneBy(["id" => $id]);

        if($restaurant)
        {
            $products = $em->getRepository(Product::class)->findBy(array("restaurant"=>[$restaurant,null],
                "productType"=>$type));

            return $this->json($products, 200);
        }

        return new JsonResponse(['message' => "restaurant not found"], Response::HTTP_NOT_FOUND);
    }
}
