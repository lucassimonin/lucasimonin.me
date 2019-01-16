<?php


/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class HomeController extends AbstractController
{
    /**
     * Dashboard Admin
     * @Route("/", name="admin_dashboard", requirements={ "_locale" = "%admin.locales%" }, options={"expose"=true})
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('admin/home/index.html.twig');
    }
}
