<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Experience;
use App\Form\Type\Content\ExperienceType;
use App\Form\Type\Content\SearchContentType;
use App\Services\Content\ContentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ExperienceController
 *
 * @package App\Controller\Admin
 * @Route("/{_locale}/admin/experiences", requirements={ "_locale" = "%admin.locales%" })
 */
class ExperienceController extends BaseContentController
{
    /**
     * @param Request     $request
     * @Route("/list", name="admin_experience_list")
     * @return Response
     */
    public function list(Request $request)
    {
        $data = $this->initSearch($request);
        $form = $this->createForm(SearchContentType::class, $data);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem('admin.experience.list.title');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getRepository(Experience::class)->queryForSearch($data->getSearchData()),
            $request->query->get('page', 1),
            20
        );

        return $this->render('admin/common/list.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView(),
            'type' => 'experience'
        ));
    }

    /**
     * @param Request     $request
     * @param ContentService $contentService
     * @Route("/create", name="admin_experience_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request, ContentService $contentService)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.experience.list.title", $this->get("router")->generate("admin_experience_list"));
        $breadcrumbs->addItem("admin.experience.title.create");

        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contentService->save($experience);
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.created'
            );

            return $this->redirectToRoute('admin_experience_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'experience',
                'type' => 'create'
            )
        );
    }

    /**
     * @param Request $request
     * @param Experience $experience
     * @param ContentService $contentService
     * @Route("/edit/{id}", name="admin_experience_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Experience $experience, ContentService $contentService)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.experience.list.title", $this->get("router")->generate("admin_experience_list"));
        $breadcrumbs->addItem("admin.experience.title.update");

        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contentService->save($experience);
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.updated'
            );

            return $this->redirectToRoute('admin_experience_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'experience',
                'type' => 'update'
            )
        );
    }

    /**
     * @param Experience $experience
     * @param ContentService $contentService
     * @Route("/delete/{id}", name="admin_experience_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Experience $experience, ContentService $contentService)
    {
        $contentService->remove($experience);
        $this->get('session')->getFlashBag()->set(
            'notice',
            'admin.flash.removed'
        );

        return $this->redirectToRoute('admin_experience_list');
    }
}
