<?php

namespace RedmineViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $apiWorker = $this->get('redmine_viewer.redmine_api_worker');

        $projects = $apiWorker->getProjects();

        if (!$projects) {
            throw new NotFoundHttpException();
        }

        return $this->render('@RedmineViewer/Default/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
