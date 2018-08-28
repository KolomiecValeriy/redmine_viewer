<?php

namespace RedmineViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function showIssuesAction($projectId)
    {
        $apiWorker = $this->get('redmine_viewer.redmine_api_worker');

        $issues = $apiWorker->getIssuesPerProject($projectId);

        return $this->render('@RedmineViewer/Default/issues.html.twig', [
            'issues' => $issues,
        ]);
    }
}
