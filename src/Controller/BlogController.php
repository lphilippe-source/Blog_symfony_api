<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\BlogContent;
use App\Controller\Services\JsonDispatch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(BlogContent::class);
        $res= $repo->findAll();
        $json = new JsonDispatch($res);
        return $json->getResponse();
    }

    /**
     * @Route("/blog/delete", name="delete")
     */
    public function deleteBlog(Request $req): Response{

        $id = $req->getContent();
        $em = $this->getDoctrine()->getManager();
        $blog= $em->getRepository(BlogContent::class)->find($id);
        $em->remove($blog);
        $em->flush();
        return new Response($id);
    }

    /**
     * @Route("/blog/submit", name="submitedData")
     */
    public function submitedData(Request $req, EntityManagerInterface $em): Response
    {
        if($req->getMethod()==='POST'){
            $data = json_decode($req->getContent(), true);
            $blogContent = new BlogContent();
           
            $em = $this->getDoctrine()->getManager();
            $userAccount= $em->getRepository(User::class)->findOneBy(array("firstName"=>$data["author"]));
            
            $blogContent->setAuthor($userAccount);
            $blogContent->setBody($data["body"]);
            $blogContent->setTitle($data["title"]);
            $em->persist($blogContent);
            $em->flush();

            return new Response($req->getContent());
        }
        return new Response('erreur dans la requête!');

    }
}
