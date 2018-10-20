<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Person;
use App\Form\Type\Content\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PersonController
 *
 * @package App\Controller\Admin
 * @Route("/{_locale}/admin/person", requirements={ "_locale" = "%admin.locales%" })
 */
class PersonController extends Controller
{
    /**
     * @param Request     $request
     * @Route("/edit", name="admin_person_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.person.title.update");

        $person = $this->getDoctrine()->getRepository(Person::class)->findAll();
        if (empty($person)) {
            $person = new Person();
        } else {
            $person = $person[0];
        }

        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($person);
            $manager->flush();
            $this->get('session')->getFlashBag()->set(
                'notice',
                'admin.flash.updated'
            );

            return $this->redirect($request->headers->get('referer'));
        }
        // View
        return $this->render(
            'admin/common/form.html.twig',
            array(
                'form' => $form->createView(),
                'title' => 'person',
                'type' => 'update'
            )
        );
    }
}
