<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dataset")
 */
class DatasetController extends AbstractController
{
    /**
     * @Route("/", name="cms_admin.dataset")
     */
    public function index(): Response
    {
        return $this->render('@CMS/admin/dataset/index.html.twig', [
            //'controller_name' => 'Admin Controller:'.$slug,
        ]);
    }
}
