<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Skill;
use App\Form\Type\Content\SkillType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SkillController
 *
 * @package App\Controller\Admin
 * @Route("/admin/skills")
 */
class SkillController extends BaseContentController
{
    /**
     * @param Request     $request
     * @Route("/list", name="admin_skill_list")
     * @return Response
     */
    public function list(Request $request): Response
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem('admin.skill.list.title');
        list($pagination, $form) = $this->initSearch($request, Skill::class);

        return $this->render('admin/common/list.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'type' => 'skill'
        ]);
    }

    /**
     * @param Request     $request
     * @Route("/create", name="admin_skill_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request): Response
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.skill.list.title", $this->get("router")->generate("admin_skill_list"));
        $breadcrumbs->addItem("admin.skill.title.create");

        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getContentManager()->save($skill);
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.created'
            );

            return $this->redirectToRoute('admin_skill_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'skill',
                'type' => 'create'
            ]
        );
    }

    /**
     * @param Request $request
     * @param Skill $skill
     * @Route("/edit/{id}", name="admin_skill_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Skill $skill): Response
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.skill.list.title", $this->get("router")->generate("admin_skill_list"));
        $breadcrumbs->addItem("admin.skill.title.update");

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getContentManager()->save($skill);
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.updated'
            );

            return $this->redirectToRoute('admin_skill_list');
        }

        return $this->render(
            'admin/common/form.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'skill',
                'type' => 'update'
            ]
        );
    }

    /**
     * @param Skill $skill
     * @Route("/delete/{id}", name="admin_skill_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Skill $skill)
    {
        $this->getContentManager()->remove($skill);
        $this->get('session')->getFlashBag()->set(
            'notice',
            'admin.flash.removed'
        );

        return $this->redirectToRoute('admin_skill_list');
    }
}
