<?php


/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller\Admin
 * @Route("/{_locale}/admin", requirements={ "_locale" = "%admin.locales%" })
 */
class HomeController extends Controller
{
    /**
     * Dashboard Admin
     * @Route("/", name="admin_dashboard", requirements={ "_locale" = "%admin.locales%" }, options={"expose"=true})
     * @return Response
     */
    public function indexAction()
    {
        $breadcrumbs = $this->container->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('admin.dashboard.label', $this->get("router")->generate("admin_dashboard"));

        return $this->render('admin/home/index.html.twig', [
        ]);
    }
}
