<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    public $JWTManager;

    public function __construct()
    {
        $this->JWTManager=JWTTokenManagerInterface::class;
    }

    public function verifyToken($request)
    {
        $token = $request->headers->get("authorization");
    }
}
