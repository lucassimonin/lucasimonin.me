<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Model\SearchUser;
use App\Form\Type\User\SearchUserType;
use App\Form\Type\User\UserType;
use App\Services\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @package App\Controller\Admin
 * @Route("/admin/users")
 */
class UserController extends AbstractController
{
    /**
     * @param Request     $request
     * @Route("/list", name="admin_user_list")
     * @return Response
     */
    public function list(Request $request)
    {
        // init search
        $data = $this->initSearch($request);

        // Init form
        $form = $this->createForm(SearchUserType::class, $data);

        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem('admin.users.list.title');


        // Paginate transform
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getRepository(User::class)->queryForSearch($data->getSearchData()),
            $request->query->get('page', 1),
            20
        );
        // Render view
        return $this->render('admin/user/index.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     *
     * @return SearchUser
     */
    protected function initSearch(Request $request)
    {
        // Filters get
        $filters = $request->query->get('search', array());

        // Init form
        $data = new SearchUser();
        $data->setId((isset($filters['id'])) ? $filters['id'] : '');
        $data->setEmail((isset($filters['email']))   ? $filters['email'] : '');
        $data->setFirstName((isset($filters['firstName'])) ? $filters['firstName'] : '');
        $data->setLastName((isset($filters['lastName'])) ? $filters['lastName'] : '');

        return $data;
    }

    /**
     * @param Request     $request
     * @param UserManager $userService
     * @Route("/create", name="admin_user_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request, UserManager $userService)
    {
        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");

        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.users.list.title", $this->get("router")->generate("admin_user_list"));
        $breadcrumbs->addItem("admin.user.title.create");

        // Init form
        $user = new User();

        $form = $this->createForm(UserType::class, $user, [
            'admin' => $this->isGranted(User::ROLE_SUPER_ADMIN)
        ]);

        // Update method
        if ('POST' === $request->getMethod()) {
            // Bind value with form
            $form->handleRequest($request);

            if ($form->isValid()) {
                // Save
                $userService->save($user);

                // Launch the message flash
                $this->get('session')->getFlashBag()->set(
                    'notice',
                    'admin.flash.created'
                );

                return $this->redirect($this->generateUrl('admin_user_edit', array(
                    'id' => $user->getId()
                )));
            }

            // Launch the message flash
            $this->get('session')->getFlashBag()->set(
                'error',
                'admin.flash.errors'
            );
        }

        // View
        return $this->render(
            'admin/user/create.html.twig',
            array(
                'form'           => $form->createView(),
                'user'           => $user,
            )
        );
    }

    /**
     * @param Request     $request
     * @param             $id
     * @param UserManager $userService
     * @Route("/edit/{id}", name="admin_user_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, User $user, UserManager $userService)
    {
        // Breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.users.list.title", $this->get("router")->generate("admin_user_list"));
        $breadcrumbs->addItem("admin.user.title.edit");

        // Init form
        $form = $this->createForm(UserType::class, $user, [
            'admin' => $this->isGranted(User::ROLE_SUPER_ADMIN)
        ]);

        // Update method
        if ('POST' === $request->getMethod()) {

            // Bind value with form
            $form->handleRequest($request);

            if ($form->isValid()) {

                // Save
                $userService->save($user);

                // Launch the message flash
                $this->get('session')->getFlashBag()->set(
                    'notice',
                    'admin.flash.updated'
                );

                return $this->redirect($request->headers->get('referer'));
            }

            // Launch the message flash
            $this->get('session')->getFlashBag()->set(
                'error',
                'admin.flash.errors'
            );
        }

        // View
        return $this->render(
            'admin/user/edit.html.twig',
            array(
                'form'           => $form->createView(),
                'user'           => $user,
            )
        );
    }

    /**
     * @param Request     $request
     * @param User        $user
     * @param UserManager $userService
     * @Route("/delete/{id}", name="admin_user_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, User $user, UserManager $userService)
    {
        $userService->remove($user);

        // Launch the message flash
        $this->get('session')->getFlashBag()->set(
            'notice',
            'L\'utilisateur "'.$user->getFirstName().' '.$user->getLastName().'" a bien été supprimé'
        );

        return $this->redirect($request->headers->get('referer'));
    }

    public function googleAuthenticatorUrl($user)
    {
        return $this->render('admin/user/parts/google_qrcode.html.twig', [
            'url' => $this->get("scheb_two_factor.security.google_authenticator")->getUrl($user)
        ]);
    }
}
