<?php

namespace App\Controller;

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
     * @Route (name="createRestaurant", path="/restaurant/create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */

    public function createRestaurant(Request $request, ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $restaurant = new Restaurant;
        $restaurantData = json_decode($request->getContent(), true);
        $restaurant->setName($restaurantData['name']);
        $restaurant->setDescription($restaurantData['description']);
        $restaurant->setAddress($restaurantData['address']);
        $restaurant->setpostalCode($restaurantData['postalCode']);
        $restaurant->setCity($restaurantData['city']);
        $restaurant->setCountry($restaurantData['country']);


        $em->persist($restaurant);
        $em->flush();

        return $this->json(['message' => 'restaurant created', "statusCode" => 200]);
    }
}
