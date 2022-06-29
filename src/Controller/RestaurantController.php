<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Country;
use App\Service\AccessControl;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Restaurant;
use Symfony\Component\Serializer\SerializerInterface;


class RestaurantController extends AbstractController
{
    private $accessControl;
    private $serializer;

    public function __construct(accessControl $accessControl, SerializerInterface $serializer)
    {
        $this->accessControl = $accessControl;
        $this->serializer=$serializer;
    }


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

        if(isset($restaurantData['country']))
        {
            $restaurant->setCountry($restaurantData['country']);
        }

        if(isset($restaurantData['phone']))
        {
            $restaurant->setCountry($restaurantData['phone']);
        }

        if(isset($restaurantData['photo']))
        {
            $restaurant->setCountry($restaurantData['photo']);
        }


        $em->persist($restaurant);
        $em->flush();

        return $this->json(['message' => 'restaurant created', "statusCode" => 200]);
    }

    /**
     * @Route (name="updateRestaurant", path="/restaurants/{id}", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateRestaurant(Request $request, ManagerRegistry $doctrine,$id)
    {

        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findBy(["id" => $id]);

        if($restaurant)
        {
            $restaurantData = json_decode($request->getContent(), true);

            if(isset($restaurantData['name']))
            {
                $restaurant->setCountry($restaurantData['name']);
            }
            if(isset($restaurantData['description']))
            {
                $restaurant->setCountry($restaurantData['description']);
            }
            if(isset($restaurantData['address']))
            {
                $restaurant->setCountry($restaurantData['address']);
            }
            if(isset($restaurantData['postalCode']))
            {
                $restaurant->setCountry($restaurantData['postalCode']);
            }
            if(isset($restaurantData['city']))
            {
                $restaurant->setCountry($restaurantData['city']);
            }
            if(isset($restaurantData['country']))
            {
                $restaurant->setCountry($restaurantData['country']);
            }
            if(isset($restaurantData['phone']))
            {
                $restaurant->setCountry($restaurantData['phone']);
            }
            if(isset($restaurantData['photo']))
            {
                $restaurant->setCountry($restaurantData['photo']);
            }
            $em->persist($restaurant);
            $em->flush();

            return $this->json($restaurant, 200);

        }

        return new JsonResponse(['message' => "Restaurant not found"], Response::HTTP_NOT_FOUND);

    }


    /**
     * @Route (name="getRestaurant", path="/restaurants/{id}", methods={"GET"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
     * @param AccessControl $accessControl
     * @return JsonResponse
     */
    public function getRestaurant(Request $request,ManagerRegistry $doctrine, $id, AccessControl $accessControl)
    {
        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }


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
        $user=$this->accessControl->verifyToken($request);

        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $restaurants = $em->getRepository(Restaurant::class)->findAll();

        return $this->json($restaurants, 200, [], ['group' => 'read']);
    }

    /**
     * @Route (name="filterRestaurants", path="/restaurants/filter", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function filterRestaurantsByCity(Request $request, ManagerRegistry $doctrine, AccessControl $accessControl, $id)
    {
        $user=$this->accessControl->verifyToken($request);

        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

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
    public function setFavoriteRestaurant(Request $request, ManagerRegistry $doctrine,$id)
    {
        $user=$this->accessControl->verifyToken($request);

        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();

        $restaurant = $em->getRepository(Restaurant::class)->findOneBy(["id" => $id]);

        if($restaurant)
        {
            $user->setRestaurant($restaurant);
            $em->persist($restaurant);
            $em->flush();


            return new JsonResponse(['message' => "Favorite Restaurant Selected"], Response::HTTP_NOT_FOUND);
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

        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findOneBy(["id" => $id]);

        if($restaurant)
        {
            return $this->json($restaurant->getOrders(), 200);
        }
        return new JsonResponse(['message' => "Restaurant not found"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="createCountry", path="/countries/", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createCountry(Request $request, ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();
        $country = new Country;
        $countryData = json_decode($request->getContent(), true);
        $country->setName($countryData['name']);
        $country>setTax($countryData['tax']);

        $em->persist($country);
        $em->flush();

        return $this->json(['message' => 'country created', "statusCode" => 200]);
    }

    /**
     * @Route (name="getCountries", path="/countries/", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCountries(Request $request, ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();
        $countries = $em->getRepository(Country::class)->findAll();
        return $this->json($countries, 200);

    }

    /**
     * @Route (name="getCountry", path="/countries/{id}", methods={"GET"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return JsonResponse
     */
    public function getCountry(Request $request,ManagerRegistry $doctrine, $id)
    {

        $em = $doctrine->getManager();
        $country = $em->getRepository(Country::class)->findOneBy(["id" => $id]);

        if($country)
        {
            return $this->json($country, 200);
        }

        return new JsonResponse(['message' => "country not found"], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route (name="createComment", path="/restaurants/{id}/comments", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createComment(Request $request, ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);

        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findOneBy(["id" => $id]);

        $comment = new Comment();
        $commentData = json_decode($request->getContent(), true);

        $comment->setDescription($commentData['description']);
        $comment->setUsers($user);
        $comment->setRestaurant($restaurant);
        $comment->setRating($commentData['rating']);

        $em->persist($comment);
        $em->flush();

        return $this->json(['message' => 'comment created', "statusCode" => 200]);
    }

    /**
     * @Route (name="getRestaurantComments", path="/restaurants/{id}/comments", methods={"GET"})
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return JsonResponse
     */
    public function getRestaurantComments(Request $request,ManagerRegistry $doctrine, $id)
    {
        $user=$this->accessControl->verifyToken($request);
        if($user==null)
        {
            $message = ["message" => "Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }
        $em = $doctrine->getManager();
        $restaurant = $em->getRepository(Restaurant::class)->findOneBy(["id" => $id]);

        if($restaurant)
        {
            $comments = $restaurant->getComments();
            return $this->json($comments, 200);
        }

        return new JsonResponse(['message' => "Comments not found"], Response::HTTP_NOT_FOUND);
    }


}
