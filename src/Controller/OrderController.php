<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use App\Entity\OrderProductAndMenu;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AccessControl;

class OrderController extends AbstractController {


    private $accessControl;

    public function __construct(accessControl $accessControl)
    {
        $this->accessControl = $accessControl;
    }


    /**
     * @Route(name="createNewOrder", path="/restaurant/{id}/orders", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function createNewOrder(Request $request, ManagerRegistry $doctrine, $id) {
        try {
            $user=$this->accessControl->verifyToken($request);
            if($user==null)
            {
                $message = ["message" => "Empty Token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }

            $em = $doctrine->getManager();

            $restaurant = $em->getRepository(Restaurant::class)->findOneBy(["id" => $id]);

            if($restaurant)
            {
                $orderData = json_decode($request->getContent(), true);
                $order = new Order;

                $order->setUser($user);
                if(isset($orderData['address']))
                {
                    $order->setAddress($orderData['address']);
                }

                if(isset($orderData['city'])){
                    $order->setCity($orderData['city']);
                }

                if(isset($orderData['postalCode'])){
                    $order->setPostalCode($orderData['postalCode']);

                }

                $order->setPrice($orderData['price']);
                $order->setType($orderData['type']);
                $order->setPayment($orderData['payment']);
                $order->setArchive((false);
                $order->setRestaurant($restaurant);
                $order->setStatut(0);

                $lastOrderId = $order->getId();
                $OrderProductAndMenu = new OrderProductAndMenu;

                foreach ($orderData['productOrdered'] as $productOrdered) {

                    $OrderProductAndMenu->setOrderId($lastOrderId);
                    $OrderProductAndMenu->setProductId($productOrdered['id']);
                }

                $em->persist($order);
                $em->flush();


            }
            return new JsonResponse(['message' => "Products not found"], Response::HTTP_NOT_FOUND);

        } catch (PDOException $e) {
                    $message = ["message" => $e];
                    return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                }
            }

    /**
     * @Route(name="changeOrderStatut", path="/orders/{id}/changestatut", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function changeOrderStatut(Request $request, ManagerRegistry $doctrine, $id) {
        try {
            $user = $this->accessControl->verifyToken($request);
            if ($user == null) {
                $message = ["message" => "Empty Token"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }

            $em = $doctrine->getManager();

            $order = $em->getRepository(Order::class)->findOneBy(["id" => $id]);

            if($order)
            {
                if ($this->accessControl->verifyStaff($user, $order)) {
                    $message = ["message" => "Access Denied"];
                    return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                }

                $orderData = json_decode($request->getContent(), true);

                $order->setStatut($orderData["statut"]);

                $em->persist($order);
                $em->flush();

                return $this->json($order, 200);

            }

            return new JsonResponse(['message' => "Order not found"], Response::HTTP_NOT_FOUND);

        }
        catch (PDOException $e) {
                $message = ["message" => $e];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }


    }
}