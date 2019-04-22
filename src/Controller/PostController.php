<?php

namespace App\Controller;

use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;

class PostController extends AbstractController
{
    /**
     * @Route("/post/new", name="new_post")
     */
    public function newPost(Request $request)
    {
        //crear nuevo objeto
        $post = new Post();
        //$post->setTitle('write a post title');
            //crear formulario
        $form=$this->createForm(PostType::class,$post);

        //handle the request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //data capture
            $post = $form->getData();
            $post->setUser($this->getUser());
            $post->setAuthor($this->getUser()->getUsername());
            // flush to DB
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('app_homepage');
        }
        //render the form
        return $this->render('post/post.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function editPost(Post $post, Request $request) {
        $form=$this->createForm(PostType::class,$post);

        //handle the request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //data capture
            $post = $form->getData();
            // flush to DB
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('post_myposts');
        }
        //render the form
        return $this->render('post/post.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function detelePost(Post $post) {
        $user = $this->getUser();

        if ($user != null && $user->getId() == $post->getUser()->getId()) {
            $manager = $this->getDoctrine()
                ->getManager();
            $manager->remove($post);
            $manager->flush();
        }

        return $this->redirectToRoute('post_myposts');
    }

    public function posts() {
        $user = $this->getUser();

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([ 'user' => $user->getId() ]);

        return $this->render('user/myposts.html.twig', [
            'posts' => $posts
        ]);
    }
}
