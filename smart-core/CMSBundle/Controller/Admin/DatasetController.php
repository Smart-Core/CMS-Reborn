<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use SmartCore\CMSBundle\Entity\Content\Dataset;
use SmartCore\CMSBundle\Form\Type\DatasetFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('@CMS/admin/dataset/index.html.twig', [
            'datasets' => $em->getRepository(Dataset::class)->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * @Route("/create/", name="cms_admin.dataset.create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $dataset = new Dataset();
        $dataset->setUser($this->getUser());

        $form = $this->createForm(DatasetFormType::class, $dataset);
        $form->remove('save');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('cms_admin.dataset');
            }

            if ($form->isValid() and $form->get('create')->isClicked()) {
                $em->persist($dataset);
                $em->flush();

                $this->addFlash('success', 'Набор данных создан.');

                return $this->redirectToRoute('cms_admin.dataset');
            }
        }

        return $this->render('@CMS/admin/dataset/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{dataset_slug}/", name="cms_admin.dataset.edit")
     */
    public function edit(string $dataset_slug, Request $request, EntityManagerInterface $em): Response
    {
        $dataset = $em->getRepository(Dataset::class)->findOneBy(['slug' => $dataset_slug]);

        if ($dataset === null) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(DatasetFormType::class, $dataset);
        $form->remove('create');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('cms_admin.dataset');
            }

            if ($form->isValid() and $form->get('save')->isClicked()) {
                $em->persist($dataset);
                $em->flush();

                $this->addFlash('success', 'Набор данных обновлён.');

                return $this->redirectToRoute('cms_admin.dataset');
            }
        }

        return $this->render('@CMS/admin/dataset/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
