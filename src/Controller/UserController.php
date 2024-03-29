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
        $user=$accessControl->verifyToken($request);

        $em = $doctrine->getManager();

        $userData = json_decode($request->getContent(), true);
        $newPassword = $userData['newPassword'];
        $oldPassword = $userData['oldPassword'];


        if (!preg_match("^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_.;])[A-Za-z\d@$!%*?&_.;]{8,}$^", $newPassword)) {
            $message = ["message" => "Password should be longer than 8 chars, Should contain at least one uppercase letter, one lowercase letter, one special chars, and one digit"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            //  return $this->json(["message" => ""]);
        }


        if (!$passwordHasher->isPasswordValid($user,$oldPassword)) {
            $message = ["message" => "Wrong old password", "status" => 400];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        if($newPassword===$oldPassword){
            $message = ["message" => "The passwords are the same"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $userData['newPassword']);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();
        $message = ["message" => "Password Modified", "status" => 200];
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
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param UserPasswordHasherInterface $passwordHasher
     * @param TokenGeneratorInterface $tokenGenerator
     * @param MailerInterface $mailer
     * @return JsonResponse
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function forgottenPassword(Request $request,ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher,
                                        TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer)
    {
        $userData = json_decode($request->getContent(), true);
        $email = $userData['email'];

        $user=$doctrine->getRepository(User::class)->findOneBy(["email" => $email]);
        $em = $doctrine->getManager();

        if($user){
            $passwordToken = $tokenGenerator->generateToken();
            $user->setPasswordToken($passwordToken);
            $em->persist($user);
            $em->flush();



            $response = new JsonResponse(
                ['passwordToken' => $passwordToken],
                Response::HTTP_ACCEPTED);

            $mail = (new Email())
                ->from('goodfood.api.contact@gmail.com')
                ->to($email)
                ->subject('Réinitialisation de mot de passe')
                ->text("Cliquez sur ce lien pour réinitialiser votre mot de passe http://localhost:3000/resetpassword?token=".$passwordToken);

            $mailer->send($mail);

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
            if (!preg_match("^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_.;])[A-Za-z\d@$!%*?&_.;]{8,}$^", $password)) {
                $message = ["message" => "Password should be longer than 8 chars, Should contain at least one uppercase letter, one lowercase letter, one special chars, and one digit"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
                //  return $this->json(["message" => ""]);
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $password);

            $user->setPassword($hashedPassword);
            $user->setPasswordToken(null);
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $message = ["message" => "Password reset"];

            return $this->json([$message,200]);

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

            if (isset($userData['firstname'])) {
                $user->setFirstName($userData['firstname']); 
            }            
            if (isset($userData['lastname'])) {
                $user->setLastName($userData['lastname']); 
            }
            if (isset($userData['email'])) {
                $user->setEmail($userData['email']); 
            }
            if (isset($userData['address'])) {
                $user->setAddress($userData['address']); 
            }
            if (isset($userData['postalCode'])) {
                $user->setPostalCode($userData['postalCode']); 
            }
            if (isset($userData['city'])) {
                $user->setCity($userData['city']); 
            }
            if (isset($userData['picture'])) {
                $user->setPicture($userData['picture']);
            }

            $em->persist($user);
            $em->flush();

            return $this->json($user,200);
        }

        $message = ["message" => "User not found"];
        return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            
    }


    /**
     * @Route(name="deleteUser", path="/users/{id}", methods={"DELETE"})
     *
     */
    public function deleteUser(Request $request, ManagerRegistry $doctrine, $id) {

        $userSession=$this->accessControl->verifyToken($request);

        if($userSession==null)
        {
            $message = ["message" => "Empty or Invalid Token"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $user = $em->getRepository(User::class)->findOneBy(["id" => $id]);

        if ($user) {

            if($this->accessControl->staffDenyAccess($user,$userSession))
            {
                $message = ["message" => "Acces Denied"];
                return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
            }

            $em->remove($user);
            $em->flush();

            return $this->json(["message" => "User deleted","statusCode"=>200],200);
        }

        $message = ["message" => "User not found"];
        return new JsonResponse($message, Response::HTTP_BAD_REQUEST);

    }



}
