<?php

use App\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 


class ProductController extends AbstractController {

    /**
     * @Route (name="selectAllProducts", path="/selectAllProducts", methods={"GET"})
     * @param Request $request 
     * @throws Exception 
     * @return JsonResponse
     */
 public function selectAllProducts($request, ManagerRegistry $doctrine ) {

        $em = $doctrine->getManager();
        $prodcuts = $em->getRepository(Product::class)->findAll();
        return $this->json($prodcuts, 200);
    
 }


 /**
  * @Route (name="selectProductsByName", path="/selectProductsByName", methods={"GET"})
  * @param Request $request 
  * @throws Exception 
  * @return JsonResponse
  */
  public function selectProductsByName($request, ManagerRegistry $doctrine, ProductRepository $productRepository) {

    $chars = json_decode($request->getContent(), true);       
    $productsFilteredByName = $productRepository->selectProductsByName($chars); 
    $arrayOfProductsFilteredByName = []; 
    foreach ($productsFilteredByName as $productFilteredByName) {
      array_push($arrayOfProductsFilteredByName, $productFilteredByName); 
        
    }
    return $this->json($arrayOfProductsFilteredByName, 200);

  }

  /**
   * @Route (name="selectProductsByType", path="/selectProductsByName", methods={"GET"})
   * @param Request $request 
   * @throws Exception 
   * @return JsonResponse 
   */
  public function selectProductsByType($request, ManagerRegistry $doctrine) {
     $type = json_decode($request->getContent(), true); 
     $em = $doctrine->getManager();  
     $productsFilteredByType = $em->getRepository(Product::class)->findBy(["id" => $type['id']]); 

     $arrayOfProductsFilteredByType = []; 

     foreach ($productsFilteredByType as $productFilteredByType) {

        array_push($arrayOfProductsFilteredByType, $productFilteredByType);
     }
    
     return $this->json($arrayOfProductsFilteredByType); 

  }

    /**
     * @Route (name="createProduct", path="/createProduct", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
  public function createProduct(Request $request, ManagerRegistry $doctrine)
  {
      $message = ["message" => "on dev"];
      return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
  }


    /**
     * @Route (name="removeProductFromDb", path="/removeProductFromDB", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
   public function removeProductFromDb()
   {
       $message = ["message" => "on dev"];
       return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
   }
}