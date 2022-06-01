<?php

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 


Class ProductController extends AbstractController {

    /**
     * @Route (name="selectAllProducts", path="/api/selectAllProducts", method={"GET"})
     * @param Request $request 
     * @throws Exception 
     * @return JsonResponse
     */
 private function selectAllProducts($request, ManagerRegistry $doctrine ) {

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
  private function selectProductsByName($request, ManagerRegistry $doctrine) {
      
  }
}