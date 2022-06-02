<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\AccessControl;
use function Symfony\Bundle\FrameworkBundle\Controller\json;

class SignUpController extends AbstractController
{


    /**
     * @Route(name="signup", path="/signup", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     */
    public function signupAsUser(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine,
                                JWTTokenManagerInterface $JWTManager)
    {
        
        try {

        $userData = json_decode($request->getContent(), true);


        $user = New User();

        $email = $userData['email'];
        $password = $userData['password'];

        $hashedPassword = $passwordHasher->hashPassword($user, $userData['password']);

        /* Données dorénavant nullables en BDD
        $lastName = $userData['lastname'];
        $firstName = $userData['firstname'];
        $address = $userData['address'];
        $city = $userData['city'];
        $postalCode = $userData['postalCode'];
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setCity($city);
        $user->setPostalCode($postalCode);
        $user->setAddress($address);
        */

        $confirmedPassword = $userData['confirmedPassword'];


        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        

        
        $em = $doctrine->getManager(); 

        $findedUser = $em->getRepository(User::class)->findOneBy(["email" => $email ]);

        if (isset($findedUser)) {
            $message = ["message" => "An account with this email is already recorded"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }
        if (!preg_match("/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/", $password)) {
            $message = ["message" => "Password should be longer than 8 chars, Should contain at least one uppercase letter, one lowercase letter, one special chars, and one digit"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST); 
          //  return $this->json(["message" => ""]);
        }
        if ($password !== $confirmedPassword) {

         $message = ["message" => "Les mots de passes ne sont pas exacts"];
         return new JsonResponse($message, Response::HTTP_BAD_REQUEST);  
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = ["message" =>"Format invalide de l'email"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);  
          }

        
        $em->persist($user); 
        $em->flush();

        $token = $JWTManager->create($user);


        $response = new JsonResponse(
            ['user' => $user,
                'token' => $token ],
            Response::HTTP_CREATED);

        $response->headers->add(["authorization" => $token]);

        return $response;


        } catch (PDOException $e) {

            $message = ["message" => $e]; 

            return new JsonResponse($message, Response::HTTP_BAD_REQUEST); 
        }

   }
   /**
    * @Route(name="createuser", path="/createuser", methods={"POST"})
    */
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine,
                               JWTTokenManagerInterface $JWTManager, AccessControl $accessControl)
    {

        $user = $accessControl->verifyToken($request);



    }
}
