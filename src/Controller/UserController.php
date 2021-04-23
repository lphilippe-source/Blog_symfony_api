<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Services\JsonDispatch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/blog/user", name="user")
     */
    public function index(Request $request)
    {
        $myfile = fopen("/home/lphilippe/Documents/tryoutPHP/symfony_apiProject/apiProject/test/truc.html", "w") or dd("Unable to open file!");
        $txt = $request->getContent();
        fwrite($myfile, $txt);
        fclose($myfile);
        $txt = json_decode($txt,true);

        $repo = $this->getDoctrine()->getRepository(User::class);
        $res = $repo->findAll();
        $json = new JsonDispatch($res);
        return $json->getResponse();
    }
     /**
     * @Route("/blog/login", name="api_login")
     * @IsGranted("ROLE_USER")
     */
    public function api_login(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        // return new JsonResponse([
        //     'email' => $user->getEmail(),
        //     'roles' => $user->getRoles(),
        // ]);
        dd($user);
        // // $text = json_decode($request->getContent());
        // // dd($text);
        // $mail = $serializer->deserialize($request->getContent(), User::class, 'json');

        // $mail = $mail->getEmail();
        // // $user = $this->getUser();
        // $repo = $this->getDoctrine()->getRepository(User::class);
        // $user = $repo->findOneByemail($mail);
        // // dd($user);
        // // $json = new JsonDispatch($res);
        // // return $json->getResponse();
        // return new JsonResponse([
        //     'email' => $mail,
        //     'roles' => $user->getRoles(),
        // ]);
    }
     /**
     * @Route("/blog/login_check", name="app_login")
     */
    public function login(): JsonResponse
    {
        // // get the login error if there is one
        // $error = $authenticationUtils->getLastAuthenticationError();
        // // last username entered by the user
        // $lastUsername = $authenticationUtils->getLastUsername();

        // return new Response($lastUsername);
        $user = $this->getUser();
        dd($user);
        return new JsonResponse([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);    
    }

    /**
     * @Route("/blog/login/signup", name="signup")
     */
    public function sign_up(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $myfile = fopen("/home/lphilippe/Documents/tryoutPHP/symfony_apiProject/apiProject/test/truc.html", "w") or dd("Unable to open file!");
        $txt = $request->getContent();
        fwrite($myfile, $txt);
        fclose($myfile);
        $txt = json_decode($txt,true);

        // dd($request->getContent());

        $user = new User();
        $hash = $encoder->encodePassword($user,$txt['password']);

        $user->setFirstName($txt['pseudo']);
        $user->setLastName($txt['pseudo']);
        $user->setHash($hash);
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
