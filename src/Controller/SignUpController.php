<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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

class SignUpController extends AbstractController
{

    private $accessControl;

    public function __construct(accessControl $accessControl)
    {
        $this->accessControl = $accessControl;
    }

    /**
     * @Route(name="signup", path="/signup", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function signupAsUser(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine,
                                 JWTTokenManagerInterface $JWTManager)
    {
        
        try {
            
            $userData = json_decode($request->getContent(), true);
            
            $user = new User();

            $email = $userData['email'];
            $password = $userData['password'];
            $hashedPassword = $passwordHasher->hashPassword($user, $userData['password']);
            $confirmedPassword = $userData['confirmedPassword'];

            $user->setEmail($email);
            $user->setPassword($hashedPassword);
            
            $em = $doctrine->getManager(); 

                $findedUser = $em->getRepository(User::class)->findOneBy(["email" => $email]);
            if (isset($findedUser)) {
                $message = ["message" => "Account already exists"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }


            if (!preg_match("^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_.;])[A-Za-z\d@$!%*?&_.;]{8,}$^", $password)) {
                $message = ["message" => "Password length"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST); 
            //  return $this->json(["message" => ""]);
            }
            if ($password !== $confirmedPassword) {
            $message = ["message" => "Passwords are not the same"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);  
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = ["message" => "Invalid email format"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }


            if (isset($userData['picture'])) {
                $user->setPicture($userData['picture']);
            }

                $user->setRoles(["client"]);

                $em->persist($user);
                $em->flush();

                $token = $JWTManager->create($user);


                $response = new JsonResponse(
                    ['user' => $user,
                        'token' => $token],
                    Response::HTTP_CREATED);

                $response->headers->add(["token" => $token]);

                return $response;
        } catch (PDOException $e) {
            $message = ["message" => $e];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

    }


    /**
     * @Route(name="createuser", path="/createuser", methods={"POST"})
     */
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine, MailerInterface $mailer)
    {
        try {
            $user=$this->accessControl->verifyToken($request);


            if($user==null)
            {
                $message = ["message" => "Token vide"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }

            if(!in_array('manager', $user->getRoles(), false))
            {
                $message = ["message" => "Wrong Access Right"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }

            $userData = json_decode($request->getContent(), true);

            $newUser = new User();
            $email = $userData['email'];
            $firstName = $userData['firstname'];
            $lastName = $userData['lastname'];
            $role=$userData['role'];

            $findedUser = $doctrine->getRepository(User::class)->findOneBy(["email" => $email]);

            if ($findedUser) {
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
            $password = implode($password);

            $hashedPassword = $passwordHasher->hashPassword($newUser, $password);

            $em = $doctrine->getManager();
            $restaurant = $user->getRestaurant();
            $newUser->setPassword($hashedPassword);
            $newUser->setEmail($email);
            $newUser->setRoles([$role]);
            $newUser->setRestaurant($restaurant);
            $newUser->setFirstName($firstName);
            $newUser->setLastName($lastName);
            $newUser->setPicture(null);

            $em->persist($newUser);
            $em->flush();

            $mail = (new Email())
                ->from('goodfood.api.contact@gmail.com')
                ->to($email)
                ->subject('Inscription')
                ->text("Votre mot de passe temporaire à modifier après votre première connexion : ".$password);

            $mailer->send($mail);

            $message = ["message" => "created","statusCode"=> 200];

            return new JsonResponse($message, Response::HTTP_CREATED);

        } catch (PDOException $e) {
            $message = ["message" => $e];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route(name="createmanager", path="/createmanager", methods={"POST"})
     */
    public function createmanager(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine, MailerInterface $mailer)
    {
        try {

            $userData = json_decode($request->getContent(), true);

            $newUser = new User();
            $email = $userData['email'];
            $firstName = $userData['firstname'];
            $lastName = $userData['lastname'];
            $password = $userData['password'];

            $findedUser = $doctrine->getRepository(User::class)->findOneBy(["email" => $email]);

            if ($findedUser) {
                $message = ["message" => "An account with this email is already recorded"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }

            $hashedPassword = $passwordHasher->hashPassword($newUser, $password);

            $em = $doctrine->getManager();

            $newUser->setPassword($hashedPassword);
            $newUser->setEmail($email);
            $newUser->setRoles(["manager"]);
            $newUser->setFirstName($firstName);
            $newUser->setLastName($lastName);
            $newUser->setPicture(null);


            $em->persist($newUser);
            $em->flush();


            $mail = (new Email())
                ->from('goodfood.api.contact@gmail.com')
                ->to($email)
                ->subject('Inscription')
                ->text("Votre mot de passe temporaire à modifier après votre première connexion : ".$password);

            $mailer->send($mail);

            $message = ["message" => "Created","statusCode"=> 200];

            return $this->json($message, 200);

        } catch (PDOException $e) {
            $message = ["message" => $e];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }
    }
}