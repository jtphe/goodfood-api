<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;

class SignUpController extends AbstractController
{   
        /**
     * @Route(name="signUpAsUser", path="/signUpAsUser", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     *
     */
    public function signUpAsUser(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine ) {
        
        try {
        $userData = json_decode($request->getContent(), true); 
        $user = New User();
        $mail = $userData['mail']; 
        $password = $userData['password']; 

        $hashedPassword = $passwordHasher->hashPassword($user, $userData['password']); 
        $lastName = $userData['lastname']; 
        $firstName = $userData['firstname']; 
        $retypedPassword = $userData['retypedpassword']; 

        $adress = $userData['adress']; 
        $city = $userData['city']; 
        $zipCode = $userData['zipCode']; 

        $user->setEmail($mail); 
        $user->setPassword($hashedPassword); 
        $user->setFirstName($firstName);
        $user->setLastName($lastName); 
        $user->setCity($city);
        $user->setPostalCode($zipCode);
        $user->setAddress($adress); 
        

        
        $em = $doctrine->getManager(); 

        $findedUser = $em->getRepository(User::class)->findOneBy(["email" => $mail ]); 

        if (isset($findedUser)) {
            $message = ["message" => "An account with this mail is already recorded"]; 
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }
        if (!preg_match("/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/", $password)) {
            $message = ["message" => "Password shoul be longer than 8 chars, Should contain at least one uppercase letter, one lowercase letter, one special chars, and one digit"]; 
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST); 
          //  return $this->json(["message" => ""]);
        }
        if ($password !== $retypedPassword) {

         $message = ["message" => "Password are not equal pleayse try again"];
         return new JsonResponse($message, Response::HTTP_BAD_REQUEST);  
        }

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $message = ["message" =>"Invalid email format"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);  
          }

        
        $em->persist($user); 
        $em->flush(); 
        $message = ["message" => "User Account created"]; 
        return new JsonResponse($message, Response::HTTP_CREATED);
        } catch (PDOException $e) {

            $message = ["message" => $e]; 

            return new JsonResponse($message, Response::HTTP_BAD_REQUEST); 

        }
        
        


   }
}
