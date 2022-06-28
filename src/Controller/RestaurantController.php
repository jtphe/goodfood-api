<?php

namespace App\Controller;

use App\Service\AccessControl;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Restaurant;

class RestaurantController extends AbstractController
{

    /**
     * @Route (name="createRestaurant", path="/restaurants/", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createRestaurant(Request $request, ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();
        $restaurant = new Restaurant;
        $restaurantData = json_decode($request->getContent(), true);
        $restaurant->setName($restaurantData['name']);
        $restaurant->setDescription($restaurantData['description']);
        $restaurant->setAddress($restaurantData['address']);
        $restaurant->setpostalCode($restaurantData['postalCode']);
        $restaurant->setCity($restaurantData['city']);


        $em->persist($restaurant);
        $em->flush();

        return $this->json(['message' => 'restaurant created', "statusCode" => 200]);
    }

    /**
     * @Route (name="getRestaurant", path="/restaurants/{id}", methods={"GET"})
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return JsonResponse
     */
    public function getRestaurant(ManagerRegistry $doctrine, $id, AccessControl $accessControl)
    {
        /* Token Verification
        $user=$accessControl->verifyToken($request);
        */

        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findBy(["id" => $id]);

        if($restaurant)
        {
            return $this->json($restaurant, 200);
        }

        return new JsonResponse(['message' => "Restaurant not found"], Response::HTTP_NOT_FOUND);
    }


    /**
    * @Route (name="selectAllRestaurants", path="/restaurants/", methods={"GET"})
    * @param Request $request
    * @throws Exception
    * @return JsonResponse
    */
    public function selectAllRestaurants(Request $request, ManagerRegistry $doctrine, AccessControl $accessControl)
    {
        /* Token Verification
        $user=$accessControl->verifyToken($request);
        */

        $em = $doctrine->getManager();
        $restaurants = $em->getRepository(Restaurant::class)->findAll();
        return $this->json($restaurants, 200);
    }

    /**
     * @Route (name="filterRestaurants", path="/restaurants/filter", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function filterRestaurantsByCity(Request $request, ManagerRegistry $doctrine, AccessControl $accessControl, $id)
    {
        /* Token Verification
        $user=$accessControl->verifyToken($request);
        */
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $city = $data['city'];

        $restaurants = $em->getRepository(Restaurant::class)->findBy(
            ['city' => $city]
        );

        return $this->json($restaurants, 200);
    }

    /**
     * @Route (name="setfavoriteRestaurant", path="/restaurants/setfavorite/{id}", methods={"PUT"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function setFavoriteRestaurant(Request $request, ManagerRegistry $doctrine, AccessControl $accessControl, $id)
    {
        /* Token Verification
        $user=$accessControl->verifyToken($request);
        */

        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findBy(["id" => $id]);

        if($restaurant)
        {
            return $this->json($restaurant, 200);
        }

        return new JsonResponse(['message' => "Restaurant not found"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="getRestaurantOrders", path="/restaurants/{id}/orders/", methods={"GET"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function getRestaurantOrders(Request $request, ManagerRegistry $doctrine, AccessControl $accessControl, $id)
    {
        /* Token Verification
        $user=$accessControl->verifyToken($request);
        */

        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findBy(["id" => $id]);

        if($restaurant)
        {
            return $this->json($restaurant->getOrders(), 200);
        }
        return new JsonResponse(['message' => "Restaurant not found"], Response::HTTP_NOT_FOUND);
    }

    }
