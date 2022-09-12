<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AccessControl;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;


class SignInController extends AbstractController
{

    /**
     * @Route(name="signin", path="/signin", methods={"POST"})
     * @param Request $request
     * @param Security $security
     * @param ManagerRegistry $doctrine
     * @param JWTTokenManagerInterface $JWTManager
     * @param UserInterface $userInterface
     * @return JsonResponse
     */
    public function SignInAsUser(Request $request, Security $security, ManagerRegistry $doctrine,
                                 JWTTokenManagerInterface $JWTManager, UserPasswordHasherInterface $passwordHasher)
    {
        $user = $security->getUser();

  
        if($user===null){
            $data = json_decode($request->getContent(), true);
            $device=$request->headers->get("device");

            $em= $doctrine->getManager();

            $email = $data["email"];
            $password = $data["password"];

            $findUser=$em->getRepository(User::class)->findOneBy(["email" => $email]);


            if($findUser!=null){


                if(in_array('client', $findUser->getRoles(), false) and $device=="web" or $device==null)
                {
                    return new JsonResponse(['message' => "Reserved for worker"], Response::HTTP_UNAUTHORIZED);
                }


                $token = $JWTManager->create($findUser);

                if (!$passwordHasher->isPasswordValid($findUser, $password)) {
                    return new JsonResponse(['message' => "Bad Password"], Response::HTTP_UNAUTHORIZED);
                }

                return $this->json(["token"=>$token,"user"=>$findUser,'restaurant' => $findUser->getRestaurant() ? $findUser->getRestaurant() : null], 200, []);


            }
            return new JsonResponse(['message' => "Bad ID"], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(['message' => "Already connected"], Response::HTTP_UNAUTHORIZED);

    }

}
