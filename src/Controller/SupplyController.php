<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Entity\Supply;
use App\Service\AccessControl;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupplyController extends AbstractController
{
    private $accessControl;

    public function __construct(accessControl $accessControl)
    {
        $this->accessControl = $accessControl;
    }

    /**
     * @Route (name="createSupplier", path="/suppliers/", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createSupplier(Request $request, ManagerRegistry $doctrine)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $supplier = new Supplier();
        $supplierData = json_decode($request->getContent(), true);
        $supplier->setName($supplierData['name']);

        $em->persist($supplier);
        $em->flush();

        return $this->json(['message' => 'supplier created', "statusCode" => 200]);
    }

    /**
     * @Route (name="selectAllSuppliers", path="/suppliers/", methods={"GET"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function selectAllSuppliers(Request $request, ManagerRegistry $doctrine)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $suppliers = $em->getRepository(Supplier::class)->findAll();
        return $this->json($suppliers, 200);
    }

    /**
     * @Route (name="getSupplier", path="/suppliers/{id}", methods={"GET"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function getSupplier(Request $request, ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $supplier = $em->getRepository(Supplier::class)->findBy(["id" => $id]);

        if($supplier)
        {
            return $this->json($supplier, 200);
        }

        return new JsonResponse(['message' => "Supplier not found"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="deleteSupplier", path="/suppliers/{id}", methods={"DELETE"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function deleteSupplier(Request $request, ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $supplier = $em->getRepository(Supplier::class)->findBy(["id" => $id]);

        if($supplier)
        {
            $em->remove($supplier);
            return new JsonResponse(['message' => "Supplier deleted"], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['message' => "Supplier not found"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="createSupply", path="/supplies/", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createSupply(Request $request, ManagerRegistry $doctrine)
    {
    $user=$this->accessControl->verifyToken($request);

    switch($user){
        case 0:
            $message = ["message" =>"Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            break;
        case 1:
            $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            break;
    }

    $em = $doctrine->getManager();
    $supply = new Supply();
    $supplyData = json_decode($request->getContent(), true);
    $supply->setName($supplyData['name']);
    $supply->setQuantity($supplyData['quantity']);
    $supply->setPrice($supplyData['price']);
    $supply->setRestaurant($user->getRestaurant());
    $supply->setSupplier($supplyData['supplier']);

    $em->persist($supply);
    $em->flush();

    return $this->json(['message' => 'supply created', "statusCode" => 200]);
    }

    /**
     * @Route (name="getSupply", path="/supplies/{id}", methods={"GET"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function getSupply(Request $request, ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $supply = $em->getRepository(Supply::class)->findBy(["id" => $id]);

        if($supply)
        {
            return $this->json($supply, 200);
        }

        return new JsonResponse(['message' => "Supply not found"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="deleteSupply", path="/supplies/{id}", methods={"DELETE"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function deleteSupply(Request $request, ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $supply = $em->getRepository(Supply::class)->findBy(["id" => $id]);

        if($supply)
        {
            $em->remove($supply);
            return new JsonResponse(['message' => "Supply deleted"], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['message' => "Supply not found"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="selectAllSupplies", path="/supplies/", methods={"GET"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function selectAllSupplies(Request $request, ManagerRegistry $doctrine)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $supplies = $em->getRepository(Supply::class)->findAll();
        return $this->json($supplies, 200);
    }

    /**
     * @Route (name="updateSupply", path="/supplies/{id}", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSupply(Request $request, ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $supply = $em->getRepository(Supply::class)->findBy(["id" => $id]);

        if($supply)
        {
            $supplyData = json_decode($request->getContent(), true);

            if(isset($supplyData['name'])){
                $supply->setName($supplyData['name']);
            }
            if(isset($supplyData['quantity'])){
                $supply->setQuantity($supplyData['quantity']);
            }
            if(isset($supplyData['price'])){
                $supply->setPrice($supplyData['price']);
            }

            $em->persist($supply);
            $em->flush();

            return $this->json(['message' => 'supply updated', "statusCode" => 200]);
        }
        $message = ["message" =>"Supply not found"];
        return new JsonResponse($message, Response::HTTP_NOT_FOUND);
    }


    /**
     * @Route (name="getSupplierSupplies", path="/suppliers/{id}/supplies", methods={"GET"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function getSupplierSupplies(Request $request, ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);

        switch($user){
            case 0:
                $message = ["message" =>"Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
            case 1:
                $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                break;
        }

        $em = $doctrine->getManager();
        $supplier = $em->getRepository(Supply::class)->findBy(["id" => $id]);

        $supplies=$supplier->getSupplies();
        return $this->json($supplies, 200);
    }


}
