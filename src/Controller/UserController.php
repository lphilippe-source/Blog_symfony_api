<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\Services\JsonDispatch;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/blog/user", name="user")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $res = $repo->findAll();
        $json = new JsonDispatch($res);
        return $json->getResponse();
    }
}
