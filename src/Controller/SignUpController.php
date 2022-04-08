<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{   
        /**
     * @Route(name="signUpAsUser", path="/api/signUpAsUser", methods={"POST"})
     * @param Request $request
     * @throws Exception
     * @return JsonResponse
     *
     */
    public function signUpAsUser(Request $request ) {
    
        $data = json_decode($request->getContent(), true); 

        $messageTest = $data['message']; 

        return $this->json(["retour" => $messageTest]); 
   }
}
