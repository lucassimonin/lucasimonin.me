<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Skill;
use App\Form\Type\Content\SearchContentType;
use App\Form\Type\Content\SkillType;
use App\Services\Content\ContentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SkillController
 *
 * @package App\Controller\Admin
 * @Route("/{_locale}/admin/skills", requirements={ "_locale" = "%admin.locales%" })
 */
class SkillController extends BaseContentController
{
    /**
     * @param Request     $request
     * @Route("/list", name="admin_skill_list")
     * @return Response
     */
    public function list(Request $request)
    {
        $data = $this->initSearch($request);
        $form = $this->createForm(SearchContentType::class, $data);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem('admin.skill.list.title');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getRepository(Skill::class)->queryForSearch($data->getSearchData()),
            $request->query->get('page', 1),
            20
        );

        return $this->render('admin/common/list.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView(),
            'type' => 'skill'
        ));
    }

    /**
     * @param Request     $request
     * @param ContentService $contentService
     * @Route("/create", name="admin_skill_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request, ContentService $contentService)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.skill.list.title", $this->get("router")->generate("admin_skill_list"));
        $breadcrumbs->addItem("admin.skill.title.create");

        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contentService->save($skill);
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.created'
            );

            return $this->redirectToRoute('admin_skill_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'skill',
                'type' => 'create'
            )
        );
    }

    /**
     * @param Request $request
     * @param Skill $skill
     * @param ContentService $contentService
     * @Route("/edit/{id}", name="admin_skill_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Skill $skill, ContentService $contentService)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.skill.list.title", $this->get("router")->generate("admin_skill_list"));
        $breadcrumbs->addItem("admin.skill.title.update");

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contentService->save($skill);
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.updated'
            );

            return $this->redirectToRoute('admin_skill_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'skill',
                'type' => 'update'
            )
        );
    }

    /**
     * @param Skill $skill
     * @param ContentService $contentService
     * @Route("/delete/{id}", name="admin_skill_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Skill $skill, ContentService $contentService)
    {
        $contentService->remove($skill);
        $this->get('session')->getFlashBag()->set(
            'notice',
            'admin.flash.removed'
        );

        return $this->redirectToRoute('admin_skill_list');
    }
}
