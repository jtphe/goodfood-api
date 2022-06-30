<?php


namespace App\Service;


use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use function Symfony\Component\String\isEmpty;

class AccessControl
{
    private $doctrine;
    private $JWTManager;
    private $accessDecisionManager;


    public function __construct(ManagerRegistry $doctrine, JWTTokenManagerInterface $JWTManager,
                                AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->doctrine=$doctrine;
        $this->JWTManager=$JWTManager;
        $this->accessDecisionManager=$accessDecisionManager;
    }

    public function verifyToken($request)
    {
        $token = $request->headers->get("token");

        if($token===null)
        {
            $message = ["message" =>"Token vide"];
            return null;
        }

        $token = $this->JWTManager->parse($token);
        $email = $token["email"];
        $em= $this->doctrine->getManager();
        $user=$em->getRepository(User::class)->findOneBy(["email" => $email]);

        if($user==null)
        {
            return null;
        }

        return $user;
    }

    public function verifyStaff($user,$entity)
    {
        if($user->getRoles()[0]!="worker" or $user->getRoles()[0]!="manager" )
        {
            return null;
        }

        if($user->getRestaurant()===$entity->getRestaurant())
        {
            return true;
        }

        return null;


    }



}