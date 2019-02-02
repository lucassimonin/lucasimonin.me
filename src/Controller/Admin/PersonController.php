<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Form\Type\Content\PersonType;
use App\Services\Content\PersonManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PersonController
 *
 * @package App\Controller\Admin
 * @Route("/admin/person")
 */
class PersonController extends AbstractController
{

    /**
     * @param Request $request
     * @param PersonManagerInterface $personManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/edit", name="admin_person_edit")
     */
    public function edit(Request $request, PersonManagerInterface $personManager): Response
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.person.title.update");
        $person = $personManager->find();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $personManager->save($person);
            $this->get('session')->getFlashBag()->set(
                'success',
                'admin.flash.updated'
            );

            return $this->redirect($request->headers->get('referer'));
        }
        // View
        return $this->render(
            'admin/common/form.html.twig',
            [
                'form' => $form->createView(),
                'title' => 'person',
                'type' => 'update'
            ]
        );
    }
}
