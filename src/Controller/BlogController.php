<?php

namespace App\Controller;

use App\Entity\BlogContent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(BlogContent::class);
        $res= $repo->findAll();

        return $this->json($res);
    }

    /**
     * @Route("/blog/submit", name="submitedData")
     */
    public function submitedData(Request $req, EntityManagerInterface $em): Response
    {
        if($req->getMethod()==='POST'){
            $data = json_decode($req->getContent(), true);
            $blogContent = new BlogContent();
            $blogContent->setAuthor($data["author"]);
            $blogContent->setBody($data["body"]);
            $blogContent->setTitle($data["title"]);
            $em->persist($blogContent);
            $em->flush();

            return new Response($req->getContent());
        }
        return new Response('erreur dans la requête!');

    }
}
