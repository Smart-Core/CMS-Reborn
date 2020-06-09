<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Controller\Admin;

use SmartCore\CMSBundle\Manager\SecurityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="cms_admin.main")
     */
    public function main(SecurityManager $securityManager, AuthenticationUtils $helper): Response
    {
        if (!$securityManager->isGranted('ROLE_ADMIN')) {
            return $this->render('@CMS/admin/login.html.twig', [
                // last username entered by the user (if any)
                'last_username' => $helper->getLastUsername(),
                // last authentication error (if any)
                'error' => $helper->getLastAuthenticationError(),
            ]);
        }

        return $this->render('@CMS/admin/dashboard.html.twig', [
            //'controller_name' => 'Admin Controller:'.$slug,
        ]);
    }

    /**
     * @param string $slug
     *
     * @return Response
     */
    public function notFound(string $slug = ''): Response
    {
        return $this->render('@CMS/Admin/dashboard.html.twig', [
            'slug' => $slug,
        ]);
    }

    /**
     * Render Elfinder FileManager.
     *
     * @Route("/files/", name="cms_admin.files")
     */
    public function elfinder(): Response
    {
        return $this->render('@CMS/Admin/elfinder.html.twig', [
            'fullscreen'    => true,
            'includeAssets' => $this->container->getParameter('fm_elfinder')['instances']['default']['include_assets'],
            'prefix'        => $this->container->getParameter('fm_elfinder')['assets_path'],
            'theme'         => $this->container->getParameter('fm_elfinder')['instances']['default']['theme'],
        ]);
    }
}
