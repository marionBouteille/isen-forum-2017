<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Forum;
use AppBundle\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/forum/{forum_id}/topic", requirements={"forum_id": "\d+"})
 */
class TopicController extends Controller
{
    /**
     * @Route("/", )
     */
    public function indexAction($id)
    {
        return $this->render('AppBundle:Topic:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/add")
     */
    public function addAction($forum_id, Request $request)
    {
        $forum = $this->getDoctrine()
            ->getRepository(Forum::class)
            ->find($forum_id);

        if($request->isMethod('post')){
            $topic = new Topic();

            $topic->setTitle($request->get('title'));
            $topic->setAuthor($request->get('author'));
            $topic->setCreation(new \DateTime());
            $topic->setForum($forum);

            //?????
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();

            return $this->redirectToRoute('app_forum_show', ['id' => $forum->getId()]);

        }
        return $this->render('AppBundle:Topic:add.html.twig', array(
            'forum'=>$forum
        ));
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name = "app_topic_show")
     */

   public function showAction($id, $forum_id)
    {
        return $this->render('AppBundle:Topic:show.html.twig', array(
            'topic' => $this->getDoctrine()
                ->getRepository(Topic::class)
                ->find($id),
            'forum'=>$this->getDoctrine()->getRepository(Forum::class)->find($forum_id)
            // ...
        ));
    }

    /**
     * @Route("/remove{id}", requirements={"id" : "\d+"}, name="app_topic_remove")
     */
    public function removeAction($forum_id,$id)
    {
        $topic = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($topic);
        $em->flush();

        return $this->redirectToRoute('app_forum_show', [
            'id'=>$forum_id
        ]);
    }

}
