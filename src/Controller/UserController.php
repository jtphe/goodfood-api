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


class UserController extends AbstractController
{


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
            $response = new JsonResponse(
                ['user' => $user],
                Response::HTTP_ACCEPTED);

            return $response;
        }

        return new JsonResponse(["message" => "l'utilisateur n'est pas trouvé"], Response::HTTP_NO_CONTENT);
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

            $message = ["message" => "Le mot de passe est mauvais"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        if ($newPassword == $oldPassword) {

            $message = ["message" => "Ce sont les mêmes mot de passe"];
            return new JsonResponse($message, Response::HTTP_BAD_REQUEST);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $userData['password']);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();
        $message = ["message" => "Mot de passe modifié"];
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

        return new JsonResponse(["message" => "l'utilisateur n'est pas trouvé"], Response::HTTP_NO_CONTENT);

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

            $message = ["message" => "mot de passe réinitialisé"];
            return new JsonResponse($message, Response::HTTP_OK);

        }

        return new JsonResponse(["message" => "l'utilisateur n'est pas trouvé"], Response::HTTP_NO_CONTENT);
    }


}
