<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Forum;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/forum/{forum_id}/topic/{topic_id}/post", requirements={"forum_id": "\d+", "topic_id": "\d+"})
 */

class PostController extends Controller
{
    /**
     * @Route("/add")
     */
    public function addAction($topic_id, $forum_id, Request $request)
    {

        $topic = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->find($topic_id);

        $forum = $this->getDoctrine()
            ->getRepository(Forum::class)
            ->find($forum_id);


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

            return $this->redirectToRoute('app_topic_show', ['forum_id'=> $forum->getId(), 'id' => $topic->getId()]);

        }
        return $this->render('AppBundle:Post:add.html.twig', array(
            'topic' => $topic

        ));

    }

    /**
     * @Route("/remove{id}", requirements={"id" : "\d+"}, name="app_post_remove")
     */
    public function removeAction($forum_id,$topic_id,$id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('app_topic_show', [
            'forum_id'=>$forum_id,
            'id'=>$topic_id
        ]);
    }

}
