<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
        $confirmedPassword = $userData['confirmedPassword'];

        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        
        $em = $doctrine->getManager(); 

        $findedUser = $em->getRepository(User::class)->findOneBy(["email" => $email ]);

        if (isset($findedUser)) {
            $message = ["message" => "Account already exists"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }
        if (!preg_match("/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/", $password)) {
            $message = ["message" => "Password length"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST); 
          //  return $this->json(["message" => ""]);
        }
        if ($password !== $confirmedPassword) {

         $message = ["message" => "Passwords are not the same"];
         return new JsonResponse($message, Response::HTTP_BAD_REQUEST);  
        }
   
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = ["message" =>"Invalid email format"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);  
        }

        $user->setRoles(["ROLE_CLIENT"]);
        
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
                               JWTTokenManagerInterface $JWTManager, AccessControl $accessControl, MailerInterface $mailer)
    {
        try {

            $user = $accessControl->verifyToken($request);

            $userData = json_decode($request->getContent(), true);

            $newUser = new User();
            $email = $userData['email'];

            $findedUser = $doctrine->getRepository(User::class)->findOneBy(["email" => $email ]);

            if (isset($findedUser)) {
                $message = ["message" => "An account with this email is already recorded"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }

            $alphabet = 'abcdefghijklmnopqrstuvwxyz';
            $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $number = "1234567890";
            $special = ".?/!";

            $password = array();

            $alphaLength = strlen($alphabet) - 1;
            $upperLength = strlen($upper) - 1;
            $numberLength = strlen($number) - 1;
            $specialLength = strlen($special) - 1;

            // On insère 6 caractères minuscules de notre alphabet
            for ($i = 0; $i < 6; $i++) {

                $a = rand(0, $alphaLength);

                $password[] = $alphabet[$a];
            }

            // On insère ensuite une majuscule, un chiffre et un caractère spécial
            $u = rand(0, $upperLength);
            $n = rand(0, $numberLength);
            $s = rand(0, $specialLength);

            $password[] = $upper[$u];
            $password[] = $number[$n];
            $password[] = $special[$s];
            // On randomize le tableau
            shuffle($password);
            // Conversion du tableau en string
            $password=implode($password);

            $hashedPassword = $passwordHasher->hashPassword($user, $password);

            $newUser->setPassword($hashedPassword);
            $newUser->setEmail($email);
            $newUser->setRoles(["ROLE_WORKER"]);
            $newUser->addRestaurant($user->getRestaurant());
            $doctrine->persist($user);
            $doctrine->flush();


            $email = (new Email())
                ->from('goodfood.api.contact@gmail.com')
                ->to($email)
                ->subject('Inscription')
                ->text($password)
                ->html('<p>Mot de passe pour goodfood</p>');

            $mailer->send($email);

            $message=["message"=>"création"];

            return new JsonResponse($message, Response::HTTP_CREATED);

        } catch (PDOException $e) {

            $message = ["message" => $e];

            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);

        }
    }
}
