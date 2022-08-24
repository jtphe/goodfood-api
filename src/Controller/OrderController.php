<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Product;
use App\Entity\Restaurant;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AccessControl;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;




class OrderController extends AbstractController {


    private $accessControl;

    public function __construct(accessControl $accessControl)
    {
        $this->accessControl = $accessControl;
    }

    /**
     * @Route(name="createNewOrder", path="/restaurants/{id}/orders", methods={"POST"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
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

                if(isset($orderData['products'])){
                    $productsList = $orderData['products'];
                }

                if(isset($orderData['menus'])){
                    $menusList = $orderData['menus'];
                }

                $order->setPrice($orderData['price']);
                $order->setType($orderData['type']);
                $order->setPayment($orderData['payment']);
                $order->setDate(new \DateTime( 'now' ));

                $order->setArchive((false));

                $order->setRestaurant($restaurant);
                $order->setStatut(0);

                if(isset($productsList)) {
                    foreach($productsList as $products) {
                        $product = $em->getRepository(Product::class)->find($products["id"]);
                        for($i = 1; $i <= $products["quantity"]; $i++){
                            $order->addProduct($product);
                        }
                    }
                }

                if(isset($menusList)){
                    foreach ($menusList as $menus) {
                        $menu = new Menu();

                        $menu->setPrice($menus["price"]);

                        $food = $em->getRepository(Product::class)->find($menus["food"]);
                        $drink = $em->getRepository(Product::class)->find($menus["drink"]);
                        $snack = $em->getRepository(Product::class)->find($menus["snack"]);

                        $menu->addProduct($food);
                        $menu->addProduct($snack);
                        $menu->addProduct($drink);

                        $menu->setOrderMenu($order);
                        $em->persist($menu);

                    }
                }

                $em->persist($order);
                $em->flush();

                return $this->json($order, 200);


            }
            return new JsonResponse(['message' => "Restaurant not found"], Response::HTTP_NOT_FOUND);

        } catch (PDOException $e) {
                    $message = ["message" => $e];
                    return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                }
    }

    /**
     * @Route(name="changeOrderStatut", path="/orders/{id}/changestatut", methods={"PUT"})
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
                if ($this->accessControl->staffDenyAccess($user, $order)) {
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

    /**
     * @Route(name="getAllOrdersByRestaurantId", path="/restaurants/{id}/orders", methods={"GET"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function getAllOrdersByRestaurantId(Request $request, ManagerRegistry $doctrine, $id) {

        $user = $this->accessControl->verifyToken($request);

        if ($user == null) {
            $message = ["message" => "Empty Token"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
              }

        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findBy(["id" => $id]);


            if (!$this->accessControl->staffDenyAccess($user, $restaurant)) {
                    $message = ["message" => "Access Denied"];
                    return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                }

            $orders = $em->getRepository(Order::class)->findBy(["restaurant" => $id]);
            
            return $this->json($orders, 200);
    }

    /**
     * @Route(name="getAllOrdersByUser", path="/users/{id}/orders", methods={"GET"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function getAllOrdersByUserId(Request $request, ManagerRegistry $doctrine, $id) {
        
        $userToken = $this->accessControl->verifyToken($request);

        if ($userToken == null) {
            $message = ["message" => "Empty Token"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $user=$em->getRepository(User::class)->findOneBy(["id" => $id]);

        if(in_array('manager', $userToken->getRoles()) || in_array('worker', $userToken->getRoles()) or $userToken===$user)
        {

            $orders = $user->getOrders();

            return $this->json($orders, 200);

        }

        return new JsonResponse(['message' => "Acces denied"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="getMenu", path="/menus/{id}", methods={"GET"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return JsonResponse
     */
    public function getMenu(Request $request, ManagerRegistry $doctrine, $id )
    {

        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $menu = $em->getRepository(Menu::class)->findOneBy(["id" => $id]);

        if($menu)
        {
            $order=$menu->getOrderMenu();
            return $this->json($order, 200);
        }

        return new JsonResponse(['message' => "Menu not found"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="getOrder", path="/orders/{id}", methods={"GET"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return JsonResponse
     */
    public function getOrder(Request $request, ManagerRegistry $doctrine, $id)
    {

        $user=$this->accessControl->verifyToken($request);

        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $order = $em->getRepository(Order::class)->findOneBy(["id" => $id]);

        if($order===null){
            return new JsonResponse(['message' => "Order not found"], Response::HTTP_NOT_FOUND);
        }

        if($this->accessControl->staffDenyAccess($user,$order) or $user->getOrders()->contains($order)==false)
        {
            return new JsonResponse(['message' => "Acces denied"], Response::HTTP_NOT_FOUND);

        }

        return $this->json($order, 200);

    }
    
}

