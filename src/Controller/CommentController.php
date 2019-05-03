<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 29/04/19
 * Time: 21:41
 */

namespace App\Controller;


use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CommentType;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    public function comment(Post $post, Request $request) {
        //crear nuevo objeto
        $comment = new Comment();
        //crear formulario
        $form=$this->createForm(CommentType::class, $comment);

        //handle the request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //data capture
            //$comment = $form->getData();
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            // flush to DB
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('app_homepage');
        }
        //render the form
        return $this->render('post/post.html.twig', [
            'form' => $form->createView()
        ]);
    }
}