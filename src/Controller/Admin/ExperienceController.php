<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Experience;
use App\Form\Type\Content\ExperienceType;
use App\Services\Content\ContentManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ExperienceController
 *
 * @package App\Controller\Admin
 * @Route("/admin/experiences")
 */
class ExperienceController extends BaseContentController
{
    /**
     * ExperienceController constructor.
     *
     * @param ContentManagerInterface $contentManager
     */
    public function __construct(ContentManagerInterface $contentManager)
    {
        parent::__construct($contentManager);
        $this->setLabelList('admin.experience.list.title');
        $this->setRouteList('admin_experience_list');
    }


    /**
     * @param Request     $request
     * @Route("/list", name="admin_experience_list")
     * @return Response
     */
    public function list(Request $request)
    {
        $this->initBreadcrumb();
        list($pagination, $form) = $this->initSearch($request, Experience::class);

        return $this->render('admin/common/list.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'type' => 'experience'
        ]);
    }

    /**
     * @param Request     $request
     * @Route("/create", name="admin_experience_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $this->initBreadcrumb(true)
             ->addItem('admin.experience.title.create');
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getContentManager()->save($experience);
            $this->sendSuccessFlash('admin.flash.created');

            return $this->redirectToRoute('admin_experience_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'experience',
                'type' => 'create'
            ]
        );
    }

    /**
     * @param Request $request
     * @param Experience $experience
     * @Route("/edit/{id}", name="admin_experience_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Experience $experience)
    {
        $this->initBreadcrumb(true)
             ->addItem('admin.experience.title.update');
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getContentManager()->save($experience);
            $this->sendSuccessFlash('admin.flash.updated');

            return $this->redirectToRoute('admin_experience_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'experience',
                'type' => 'update'
            ]
        );
    }

    /**
     * @param Experience $experience
     * @Route("/delete/{id}", name="admin_experience_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Experience $experience)
    {
        $this->getContentManager()->remove($experience);
        $this->sendSuccessFlash('admin.flash.removed');

        return $this->redirectToRoute('admin_experience_list');
    }
}
