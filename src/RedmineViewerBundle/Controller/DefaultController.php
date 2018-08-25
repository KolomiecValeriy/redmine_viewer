<?php

namespace RedmineViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@RedmineViewer/Default/index.html.twig');
    }
}
