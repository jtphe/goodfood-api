<?php


namespace App\Service;


use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
        $token = $request->headers->get("authorization");

        if($token===null)
        {
            $message = ["message" =>"Token vide"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $token = $this->JWTManager->parse($token);
        $email = $token["email"];
        $em= $this->doctrine->getManager();
        $user=$em->getRepository(User::class)->findOneBy(["email" => $email]);

        if($user==null)
        {
            $message = ["message" =>"Utilisateur introuvable ou erreur de token"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        return $user;
    }

}