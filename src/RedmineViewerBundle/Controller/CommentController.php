<?php

namespace RedmineViewerBundle\Controller;

use RedmineViewerBundle\Entity\Comment;
use RedmineViewerBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends Controller
{
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addAction($issueId, Request $request)
    {
        $commentForm = $this->createForm(CommentType::class);

        $apiWorker = $this->get('redmine_viewer.redmine_api_worker');

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $comment = new Comment();

            $comment
                ->setMessage($commentForm->get('message')->getData())
                ->setIssueId($commentForm->get('issueId')->getData())
                ->setAuthorName($this->getUser()->getUsername())
            ;

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('redmine_viewer_homepage');
        }

        $issue = 0;
        if ($issueId) {
            $issue = $apiWorker->getIssueById($issueId);
        }

        if (!$issue) {
            throw new NotFoundHttpException();
        }

        return $this->render('@RedmineViewer/Commetn/add.html.twig', [
            'commentForm' => $commentForm->createView(),
            'issue' => $issue,
        ]);
    }
}
