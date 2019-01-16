<?php

namespace App\Controller\Front;

use App\Entity\Experience;
use App\Entity\Person;
use App\Entity\Skill;
use App\Entity\Work;
use App\Services\Content\ContentService;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HomeController
 *
 * @package App\Controller\Front
 */
class HomeController extends AbstractController
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
     * @Route("/download/pdf", name="download_pdf")
     * @return Response
     */
    public function download() : Response
    {
        $person = $this->getDoctrine()->getRepository(Person::class)->findAll();
        if (!empty($person)) {
            $person = $person[0];
        }

        $html = $this->renderView('front/pdf/index.html.twig', [
            'person' => $person
        ]);

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                'encoding' => 'utf-8'
            ]),
            'cv_lucas-simonin.pdf'
        );
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
     * @param Request $request
     * @param ContentService $contentService
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function experiences(Request $request, ContentService $contentService, $pdf) : Response
    {
        $orderBy['c.startDate'] = 'desc';
        $locale = $request->getLocale();

        return $this->render(
            'front/parts/experiences.html.twig',
            [
                'pdf' => $pdf,
                'xps' => $contentService->getContents(
                    Experience::class,
                    'app.experiences.' . $locale,
                    [],
                    $orderBy
                )
            ]
        );
    }

    /**
     * Part skills
     * @param Request $request
     * @param ContentService $contentService
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function skills(Request $request, ContentService $contentService, $pdf) : Response
    {
        $orderBy['c.note'] = 'desc';
        $locale = $request->getLocale();

        return $this->render(
            'front/parts/skills.html.twig',
            [
                'skills' => $contentService->getContents(
                    Skill::class,
                    'app.skills.' . Skill::SKILL_TYPE_SKILL . '.' . $locale,
                    ['c.type' => ['=', Skill::SKILL_TYPE_SKILL]],
                    $orderBy
                ),
                'languages' => $contentService->getContents(
                    Skill::class,
                    'app.skills.' . Skill::SKILL_TYPE_LANGUAGE . '.' . $locale,
                    ['c.type' => ['=', Skill::SKILL_TYPE_LANGUAGE]],
                    $orderBy
                ),
                'tools' => $contentService->getContents(
                    Skill::class,
                    'app.skills.' . Skill::SKILL_TYPE_TOOLS . '.' . $locale,
                    ['c.type' => ['=', Skill::SKILL_TYPE_TOOLS]],
                    $orderBy
                ),
                'pdf' => $pdf
            ]
        );
    }

    /**
     * Part works
     * @param Request $request
     * @param ContentService $contentService
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function works(Request $request, ContentService $contentService) : Response
    {
        $locale = $request->getLocale();

        return $this->render(
            'front/parts/works.html.twig',
            [
                'works' => $contentService->getContents(
                    Work::class,
                    'app.works.' . $locale
                )
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
