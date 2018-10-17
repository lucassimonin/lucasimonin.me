<?php

namespace App\Controller\Front;

use App\Entity\Person;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomeController
 *
 * @package App\Controller\Front
 */
class HomeController extends Controller
{
    /**
     * Homepage
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index() : Response
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findAll();
        if (!empty($person)) {
            $person = $person[0];
        }

        return $this->render('front/index.html.twig', [
            'person' => $person
        ]);
    }

    /**
     * Homepage
     * @Route("/{_locale}", requirements={ "_locale" = "%app.locales%" }, name="homepage_locale")
     * @return Response
     */
    public function localeIndex() : Response
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findAll();
        if (!empty($person)) {
            $person = $person[0];
        }

        return $this->render('front/index.html.twig', [
            'person' => $person
        ]);
    }

    /**
     * Part experiences
     * @return Response
     */
    public function experiences() : Response
    {
        /*$this->coreHelper = $this->container->get('app.core_helper');
        $xpContentTypeIdentifier = $this->container->getParameter('app.experience.content_type.identifier');
        $xpLocationId = $this->container->getParameter('app.xp.locationid');
        $params['educations'] = $this->coreHelper->getObjectByType($this->container->getParameter('app.type.education'),
            $xpLocationId,
            $xpContentTypeIdentifier);
        $params['xps'] = $this->coreHelper->getObjectByType($this->container->getParameter('app.type.work'),
            $xpLocationId,
            $xpContentTypeIdentifier);*/

        return $this->render(
            'front/parts/experiences.html.twig',
            [
                'educations' => [],
                'xps' => []
            ]
        );

    }

    /**
     * Part skills
     * @return Response
     */
    public function skills() : Response
    {
       /* $this->coreHelper = $this->container->get('app.core_helper');
        $skillContentTypeIdentifier = $this->container->getParameter('app.skill.content_type.identifier');
        $skillsLocationId = $this->container->getParameter('app.skills.locationid');
        $params['languages'] = $this->coreHelper->getObjectByType($this->container->getParameter('app.type.language'),
            $skillsLocationId,
            $skillContentTypeIdentifier,
            'note',
            Query::SORT_DESC);
        $params['tools'] = $this->coreHelper->getObjectByType($this->container->getParameter('app.type.tools'),
            $skillsLocationId,
            $skillContentTypeIdentifier,
            'note',
            Query::SORT_DESC);
        $params['skills'] = $this->coreHelper->getObjectByType($this->container->getParameter('app.type.skill'),
            $skillsLocationId,
            $skillContentTypeIdentifier,
            'note',
            Query::SORT_DESC);*/

        return $this->render(
            'front/parts/skills.html.twig',
            [
                'skills' => [],
                'languages' => [],
                'tools' => []
            ]
        );

    }

    /**
     * Part works
     * @return Response
     */
    public function works() : Response
    {
        /*$this->coreHelper = $this->container->get('app.core_helper');
        $worksItemContentTypeIdentifier = $this->container->getParameter('app.work.content_type.identifier');
        $worksLocationId = $this->container->getParameter('app.works.locationid');
        $params['works'] = $this->coreHelper->getChildrenObject([$worksItemContentTypeIdentifier], $worksLocationId);*/

        return $this->render(
            'front/parts/works.html.twig',
            [
                'works' => []
            ]
        );
    }

    public function meta()
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findAll();
        if (!empty($person)) {
            $person = $person[0];
        }

        return $this->render('front/pagelayout/page_head.html.twig', [
            'person' => $person
        ]);
    }
}
