<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Product;
use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
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
     * @Route(name="createNewOrder", path="/restaurants/{id}/orders", methods={"POST"})
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

            $restaurant = $em->getRepository(Restaurant::class)->find($id);

            if($restaurant)
            {
                $orderData = json_decode($request->getContent(), true);
                $order = new Order;

                $order->setUser($user);

                if(isset($orderData['address']))
                {
                    $order->setAddress($orderData['address']);
                }

                if(isset($orderData['city']))
                {
                    $order->setCity($orderData['city']);
                }

                if(isset($orderData['postalCode']))
                {
                    $order->setPostalCode($orderData['postalCode']);
                }

                $products = $orderData['products'];
                $menus = $orderData['menus'];

                if(isset($menus)){
                    foreach ($menus as $menuData) {
                        $menu = new Menu();
                        $menu->setPrice($menuData[0]);

                        foreach ($menuData[1] as $product) {
                            $product = $em->getRepository(Product::class)->find($product);
                            $menu->addProduct($product);
                        }

                        $menu->setOrderMenu($order);
                        $em->persist($menu);
                    }
                }

                if(isset($products)) {
                    foreach($products as $product) {
                        $product = $em->getRepository(Product::class)->find($product);
                        $order->addProduct($product);
                    }
                }

                $order->setPrice($orderData['price']);
                $order->setType($orderData['type']);
                $order->setPayment($orderData['payment']);
                $order->setDate(new \DateTime( 'now' ));

                $order->setArchive((false));

                $order->setRestaurant($restaurant);
                $order->setStatut(0);



                $em->persist($order);
                $em->flush();

                return new JsonResponse(['message' => "Order Created"], Response::HTTP_CREATED);


            }
            return new JsonResponse(['message' => "Restaurant not found"], Response::HTTP_NOT_FOUND);

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

                $order->setStatus($orderData["statut"]);

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