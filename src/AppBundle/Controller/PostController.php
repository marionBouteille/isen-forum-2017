<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/add")
     */
    public function addAction($topic_id, Request $request)
    {


        $topic = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->find($topic_id);

        if ($request->isMethod('post')) {
            $post = new Post();

            $post->setContent($request->get('content'));
            $post->setAuthor($request->get('author'));
            $post->setCreation(new \DateTime());
            $post->setTopic($topic);

            //?????
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('app_topic_show', ['id' => $topic->getId()]);

        }
        return $this->render('AppBundle:Post:add.html.twig', array(
            'topic' => $topic
        ));

    }

}
