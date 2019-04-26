<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\User;
use App\Model\SearchUser;
use App\Form\Type\User\SearchUserType;
use App\Form\Type\User\UserType;
use App\Services\User\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @package App\Controller\Admin
 * @Route("/admin/users")
 */
class UserController extends BaseController
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * UserController constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->setLabelList('admin.users.list.title');
        $this->setRouteList('admin_user_list');
        $this->userManager = $userManager;
    }

    /**
     * @param Request     $request
     * @Route("/list", name="admin_user_list")
     * @return Response
     */
    public function list(Request $request): Response
    {
        $this->initBreadcrumb();
        list($pagination, $form) = $this->initSearch($request);

        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/create", name="admin_user_create")
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        $this->initBreadcrumb(true)
            ->addItem("admin.user.title.create");
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'admin' => $this->isGranted(User::ROLE_SUPER_ADMIN)
        ]);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getUserManager()->save($user);
                $this->setFlashBag('success', 'admin.flash.created');

                return $this->redirect($this->generateUrl('admin_user_edit', [
                    'id' => $user->getId()
                ]));
            }
            $this->setFlashBag('error', 'admin.flash.errors');
        }

        return $this->render(
            'admin/user/create.html.twig',
            [
                'form'           => $form->createView(),
                'user'           => $user,
            ]
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @Route("/edit/{id}", name="admin_user_edit")
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Request $request, User $user): Response
    {
        $this->initBreadcrumb(true)
             ->addItem("admin.user.title.edit");
        $form = $this->createForm(UserType::class, $user, [
            'admin' => $this->isGranted(User::ROLE_SUPER_ADMIN)
        ]);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getUserManager()->save($user);
                $this->setFlashBag('notice', 'admin.flash.updated');

                return $this->redirect($this->generateUrl('admin_user_edit', [
                    'id' => $user->getId()
                ]));
            }
            $this->setFlashBag('error', 'admin.flash.errors');
        }

        return $this->render(
            'admin/user/edit.html.twig',
            [
                'form'           => $form->createView(),
                'user'           => $user,
            ]
        );
    }

    /**
     * @param Request     $request
     * @param User        $user
     * @Route("/delete/{id}", name="admin_user_delete")
     * @return RedirectResponse
     */
    public function delete(Request $request, User $user): Response
    {
        $this->getUserManager()->remove($user);
        $this->setFlashBag('notice', 'admin.flash.removed');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param $user
     *
     * @return Response
     */
    public function googleAuthenticatorUrl(User $user): Response
    {
        return $this->render('admin/user/parts/google_qrcode.html.twig', [
            'url' => $this->getUserManager()->getQRCodeUrl($user)
        ]);
    }

    private function getUserManager(): UserManagerInterface
    {
        return $this->userManager;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function initSearch(Request $request): array
    {
        $search = new SearchUser($request->query->all());
        $form = $this->createForm(SearchUserType::class, $search);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getRepository(User::class)->queryForSearch($search),
            $search->getPage(),
            SearchUser::$limit
        );

        return [$pagination, $form];
    }
}
