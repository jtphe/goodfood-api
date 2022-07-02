<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use App\Entity\OrderProductAndMenu;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;


class OrderController extends AbstractController {

    /**
     * @Route(name="createNewOrder", path="/api/CreateNewOrder", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function createNewOrder(Request $request, ManagerRegistry $doctrine) {
        // Do not forget to implement token management 
        try {
            $orderData = json_decode($request->getContent(), true);
            $order = new Order; 

            $order->setUsers($orderData['userId']);
            $order->setAddress($orderData['address']); 
            $order->setCity($orderData['city']); 
            $order->setPostalCode($orderData['postalCode']); 
            $em = $doctrine->getManager(); 
            $em->persist($order); 
            $em->flush(); 

            $lastOrderId = $order->getId();
            $OrderProductAndMenu = new OrderProductAndMenu; 

            
            foreach ($orderData['productOrdered'] as $productOrdered) {

                $OrderProductAndMenu->setOrderId($lastOrderId); 
                $OrderProductAndMenu->setProductId($productOrdered['id']); 

            }
        } catch (PDOException $e) {
            $message = ["message" => $e];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }
    }
}