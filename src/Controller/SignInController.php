<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;


class SignInController extends AbstractController
{
    /**
     * @Route(name="signInAsUser", path="/signInAsUser", methods={"POST"})
     * @param Request $request
     * @param Security $security
     * @param ManagerRegistry $doctrine
     * @param JWTTokenManagerInterface $JWTManager
     * @param UserInterface $userInterface
     * @return JsonResponse
     */
    public function SignInAsUser(Request $request, Security $security, ManagerRegistry $doctrine,
                                 JWTTokenManagerInterface $JWTManager)
    {
        $user = $security->getUser();

        if($user===null){
            $data = json_decode($request->getContent(), true);

            $em= $doctrine->getManager();

            $email = $data["email"];
            $password = $data["password"];

            $findUser=$em->getRepository(User::class)->findOneBy(["email" => $email]);


            if($findUser!=null and $findUser->getPassword() === $password){



                return new JsonResponse(
                    ["token" => $JWTManager->create($findUser),
                    'user' => $findUser->getRoles()],
                    Response::HTTP_ACCEPTED);
            }

            return new JsonResponse(['message' => "Mauvais identifiants"], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(['message' => "Déja connecté"], Response::HTTP_UNAUTHORIZED);

    }

}
