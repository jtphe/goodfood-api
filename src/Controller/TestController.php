<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    /**
     * @Rest\Get(/test)
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function test(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $users = $doctrine->getRepository(User::class)->findAll();

    }
}
