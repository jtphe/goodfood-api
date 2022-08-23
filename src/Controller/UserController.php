<?php

namespace App\Controller;


use App\Entity\User;
use App\Service\AccessControl;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;


class UserController extends AbstractController
{

    private $accessControl;
    private $serializer;

    public function __construct(accessControl $accessControl, SerializerInterface $serializer)
    {
        $this->accessControl = $accessControl;
        $this->serializer=$serializer;
    }



    /**
     * @Route(name="getUser", path="/getuser", methods={"GET"})
     * @param Request $request
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     * @throws JWTDecodeFailureException
     */
    public function Profile( Request $request, AccessControl $accessControl)
    {

        $user=$accessControl->verifyToken($request);


        if($user)
        {

            return $this->json($user, 200, [], ['group' => 'read']);
        }

        return new JsonResponse(["message" => "User not found"], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(name="changepassword", path="/changepassword", methods={"PUT"})
     */
    public function changePassword(Request $request,
                                   ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher,
                                    AccessControl $accessControl)
    {
        $accessControl->verifyToken($request);

        $user=$doctrine->getRepository(User::class)->findOneBy(["email" => $email]);

        $em = $doctrine->getManager();

        $password = $user->getPassword();


        $userData = json_decode($request->getContent(), true);
        $newPassword = $userData['password'];
        $oldPassword = $userData['oldPassword'];


        if (!preg_match("/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/", $newPassword)) {
            $message = ["message" => "Password should be longer than 8 chars, Should contain at least one uppercase letter, one lowercase letter, one special chars, and one digit"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            //  return $this->json(["message" => ""]);
        }

        if ($oldPassword != $password) {

            $message = ["message" => "Wrong Password"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        if ($newPassword == $oldPassword) {

            $message = ["message" => "Not the same passwords"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $userData['password']);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();
        $message = ["message" => "Password Modified"];
        return new JsonResponse($message, Response::HTTP_CREATED);
    }


    /**
     * @Route(name="email", path="/email", methods={"GET"})
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('goodfood.api.contact@gmail.com')
            ->to('soufi.chamalaine@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('test')
            ->text('test')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
    }

    /**
     * @Route(name="forgottenpassword", path="/forgottenpassword", methods={"POST"})
     */
    public function forgottenPassword(Request $request,ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher,
                                        TokenGeneratorInterface $tokenGenerator)
    {
        $userData = json_decode($request->getContent(), true);
        $email = $userData['email'];

        $user=$doctrine->getRepository(User::class)->findOneBy(["email" => $email]);
        $em = $doctrine->getManager();

        if($user){
            $passwordToken = $tokenGenerator->generateToken();
            $user->setPasswordToken($passwordToken);
            $em->persist($user);

            $response = new JsonResponse(
                ['passwordToken' => $passwordToken],
                Response::HTTP_ACCEPTED);
            return $response;

        }

        return new JsonResponse(["message" => "User not found"], Response::HTTP_NO_CONTENT);

    }

    /**
     *@Route(name="resetpassword", path="/resetpassword", methods={"POST"})
     */
    public function resetPassword(Request $request,ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        $data= json_decode($request->getContent(), true);

        $passwordToken = $data["passwordToken"];
        $password = $data["password"];

        $user=$doctrine->getRepository(User::class)->findOneBy(["passwordToken" => $passwordToken]);

        if($user)
        {
            if (!preg_match("/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/", $password)) {
                $message = ["message" => "Password should be longer than 8 chars, Should contain at least one uppercase letter, one lowercase letter, one special chars, and one digit"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                //  return $this->json(["message" => ""]);
            }

            $user->setPassword($password);
            $user->setPasswordToken(null);

            $message = ["message" => "Password reinitialised"];
            return new JsonResponse($message, Response::HTTP_OK);

        }

        return new JsonResponse(["message" => "User not found"], Response::HTTP_NO_CONTENT);
    }

    /**
     *@Route(name="getUserRestaurant", path="/users/restaurants", methods={"GET"})
     */
    public function getUserRestaurant(Request $request,ManagerRegistry $doctrine)
    {
        $user=$this->accessControl->verifyToken($request);

        if($user==null)
        {
            $message = ["message" => "Empty Token"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();

        $restaurant=$user->getRestaurant();
        if($restaurant)
        {
            return $this->json($restaurant,200);

        }

        return new JsonResponse(['message' => "Restaurant not selected"], Response::HTTP_NOT_FOUND);

    }

    /**
     * @Route(name="updateUser", path="/users/{id}", methods={"PUT"}) 
     * 
    */
    public function updateUser(Request $request, ManagerRegistry $doctrine, $id) {
        
        $session=$this->accessControl->verifyToken($request);

        if($session==null)
        {
            $message = ["message" => "Empty or Invalid Token"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        } 
        $em = $doctrine->getManager();
        $user = $em->getRepository(User::class)->findOneBy(["id" => $id]); 
        if ($user) {
            $userData = json_decode($request->getContent(), true); 
            if ($userData['firstName']) {
                $user->setFirstName($userData['firstName']); 
            }
            if ($userData['lastName']) {
                $user->setLastName($userData['lastName']); 
            }
            if ($userData['address']) {
                $user->setAddress($userData['address']); 
            }
            if ($userData['postalCode']) {
                $user->setPostalCode($userData['postalCode']); 
            }
            if ($userData['city']) {
                $user->setCity($userData['city']); 
            }
            if ($userData['picture']) {
                $user->setPicture($userData['picture']);
            }

            $em->persist($user);
            $em->flush();
            $message = ["message" => "User updated", "status" => 200];
            return new JsonResponse($message, Response::HTTP_CREATED);      
        }
            
    }



}
