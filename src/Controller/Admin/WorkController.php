<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Work;
use App\Form\Type\Content\WorkType;
use App\Services\Content\ContentManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WorkController
 *
 * @package App\Controller\Admin
 * @Route("/admin/works")
 */
class WorkController extends BaseContentController
{

    /**
     * WorkController constructor.
     *
     * @param ContentManagerInterface $contentManager
     */
    public function __construct(ContentManagerInterface $contentManager)
    {
        parent::__construct($contentManager);
        $this->setLabelList('admin.work.list.title');
        $this->setRouteList('admin_work_list');
    }

    /**
     * @param Request     $request
     * @Route("/list", name="admin_work_list")
     * @return Response
     */
    public function list(Request $request): Response
    {
        $this->initBreadcrumb();
        list($pagination, $form) = $this->initSearch($request, Work::class);

        return $this->render('admin/common/list.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'type' => 'work'
        ]);
    }

    /**
     * @param Request     $request
     * @Route("/create", name="admin_work_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request): Response
    {
        $this->initBreadcrumb(true)
             ->addItem("admin.work.title.create");
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getContentManager()->save($work);
            $this->setFlashBag('success', 'admin.flash.created');

            return $this->redirectToRoute('admin_work_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'work',
                'type' => 'create'
            ]
        );
    }

    /**
     * @param Request $request
     * @param Work $work
     * @Route("/edit/{id}", name="admin_work_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Work $work): Response
    {
        $this->initBreadcrumb(true)
             ->addItem("admin.work.title.update");
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getContentManager()->save($work);
            $this->setFlashBag('success', 'admin.flash.updated');

            return $this->redirectToRoute('admin_work_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'work',
                'type' => 'update'
            ]
        );
    }

    /**
     * @param Work $work
     * @Route("/delete/{id}", name="admin_work_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Work $work)
    {
        $this->getContentManager()->remove($work);
        $this->setFlashBag('warning', 'admin.flash.removed');

        return $this->redirectToRoute('admin_work_list');
    }
}
