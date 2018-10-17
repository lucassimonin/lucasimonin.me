<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Work;
use App\Form\Type\Content\SearchContentType;
use App\Form\Type\Content\WorkType;
use App\Services\Content\ContentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WorkController
 *
 * @package App\Controller\Admin
 * @Route("/{_locale}/admin/works", requirements={ "_locale" = "%admin.locales%" })
 */
class WorkController extends BaseContentController
{
    /**
     * @param Request     $request
     * @Route("/list", name="admin_work_list")
     * @return Response
     */
    public function list(Request $request)
    {
        $data = $this->initSearch($request);
        $form = $this->createForm(SearchContentType::class, $data);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem('admin.work.list.title');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getRepository(Work::class)->queryForSearch($data->getSearchData()),
            $request->query->get('page', 1),
            20
        );

        return $this->render('admin/common/list.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView(),
            'type' => 'work'
        ));
    }

    /**
     * @param Request     $request
     * @param ContentService $contentService
     * @Route("/create", name="admin_work_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request, ContentService $contentService)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.work.list.title", $this->get("router")->generate("admin_work_list"));
        $breadcrumbs->addItem("admin.work.title.create");

        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contentService->save($work);
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.created'
            );

            return $this->redirectToRoute('admin_work_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'work',
                'type' => 'create'
            )
        );
    }

    /**
     * @param Request $request
     * @param Work $work
     * @param ContentService $contentService
     * @Route("/edit/{id}", name="admin_work_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Work $work, ContentService $contentService)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.work.list.title", $this->get("router")->generate("admin_work_list"));
        $breadcrumbs->addItem("admin.work.title.update");

        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contentService->save($work);
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.updated'
            );

            return $this->redirectToRoute('admin_work_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'work',
                'type' => 'update'
            )
        );
    }

    /**
     * @param Work $work
     * @param ContentService $contentService
     * @Route("/delete/{id}", name="admin_work_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Work $work, ContentService $contentService)
    {
        $contentService->remove($work);
        $this->get('session')->getFlashBag()->set(
            'notice',
            'admin.flash.removed'
        );

        return $this->redirectToRoute('admin_work_list');
    }
}
