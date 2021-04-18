<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Services\JsonDispatch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    /**
     * @Route("/blog/signup", name="signup")
     */
    public function sign_up(Request $request, EntityManagerInterface $em)
    {
        // var_dump("toto");
        $myfile = fopen("/home/lphilippe/Documents/tryoutPHP/symfony_apiProject/apiProject/test/truc.html", "w") or dd("Unable to open file!");
        $txt = $request->getContent();
        fwrite($myfile, $txt);
        // $txt = "Jane Doe\n";
        // fwrite($myfile, $txt);
        fclose($myfile);
        $txt = json_decode($txt,true);

        // dd($request->getContent());
        $user = new User();
        $user->setFirstName($txt['pseudo']);
        $user->setLastName($txt['pseudo']);
        $user->setHash($txt['password']);
        $user->setEmail($txt['mail']);
        $user->setIntroduction('default');
        $user->setDescription('default');
        $user->setSlug('default');
        $user->setText('default');

        $em->persist($user);
        $em->flush();
        return new Response('utilisateur créer');
    }
}
