<?php

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 


Class ProductController extends AbstractController {

    /**
     * @Route (name="selectAllProducts", path="/api/selectAllProducts", method={"GET"})
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
  * @Route (name="selectProductsByName", path="/api/selectProductsByName", method={"GET"})
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
   * @Route (name="selectProductsByType", path="/api/selectProductsByName", method={"GET"})
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
   * @Route (name='createProduct', path="/api/createProduct", method={"POST"})
   * @param Request $request 
   * @throws Exception 
   * @return JsonResponse 
   */
  public function createProduct($request, ManagerRegistry $doctrine) {
     

  }
   /**
    * @Route (name="removeProductFromDb", path="/api/removeProductFromDB", method={"DELETE"})
    * @param Request $request 
    * @throws Exception 
    */
   public function removeProductFromDb() { 

    

   }
}