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
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * UserController constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param Request     $request
     * @Route("/list", name="admin_user_list")
     * @return Response
     */
    public function list(Request $request): Response
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem('admin.users.list.title');
        list($pagination, $form) = $this->initSearch($request);

        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function initSearch(Request $request): array
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

    /**
     * @param Request $request
     * @Route("/create", name="admin_user_create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));
        $breadcrumbs->addItem("admin.users.list.title", $this->get("router")->generate("admin_user_list"));
        $breadcrumbs->addItem("admin.user.title.create");

        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'admin' => $this->isGranted(User::ROLE_SUPER_ADMIN)
        ]);
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getUserManager()->save($user);
                $this->get('session')->getFlashBag()->set(
                    'success',
                    'admin.flash.created'
                );

                return $this->redirect($this->generateUrl('admin_user_edit', [
                    'id' => $user->getId()
                ]));
            }
            $this->get('session')->getFlashBag()->set(
                'error',
                'admin.flash.errors'
            );
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function edit(Request $request, User $user): Response
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

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getUserManager()->save($user);
                $this->get('session')->getFlashBag()->set(
                    'notice',
                    'admin.flash.updated'
                );

                return $this->redirect($request->headers->get('referer'));
            }
            $this->get('session')->getFlashBag()->set(
                'error',
                'admin.flash.errors'
            );
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, User $user): Response
    {
        $this->getUserManager()->remove($user);
        $this->get('session')->getFlashBag()->set(
            'warning',
            'L\'utilisateur "'.$user->getFirstName().' '.$user->getLastName().'" a bien été supprimé'
        );

        return $this->redirect($request->headers->get('referer'));
    }

    public function googleAuthenticatorUrl($user): Response
    {
        return $this->render('admin/user/parts/google_qrcode.html.twig', [
            'url' => $this->get("scheb_two_factor.security.google_authenticator")->getUrl($user)
        ]);
    }

    private function getUserManager()
    {
        return $this->userManager;
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'knp_paginator' => '?knp_paginator',
            'white_october_breadcrumbs' => '?white_october_breadcrumbs',
        ]);
    }
}
