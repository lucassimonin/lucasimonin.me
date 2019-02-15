<?php

namespace App\Controller\Front;

use App\Entity\Experience;
use App\Entity\Skill;
use App\Entity\Work;
use App\Services\Content\ContentManagerInterface;
use App\Services\Content\PersonManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
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
     * @var ContentManagerInterface
     */
    private $contentManager;
    /**
     * @var PersonManagerInterface
     */
    private $personManager;

    /**
     * HomeController constructor.
     * @param ContentManagerInterface $contentManager
     * @param PersonManagerInterface $personManager
     */
    public function __construct(ContentManagerInterface $contentManager, PersonManagerInterface $personManager)
    {
        $this->contentManager = $contentManager;
        $this->personManager = $personManager;
    }


    /**
     * Homepage
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index() : Response
    {
        return $this->render('front/index.html.twig', [
            'person' => $this->getPersonManager()->find()
        ]);
    }

    /**
     * Homepage
     * @Route("/download/pdf", name="download_pdf")
     * @param Pdf $snappy
     * @return Response
     */
    public function download(Pdf $snappy) : Response
    {
        $html = $this->renderView('front/pdf/index.html.twig', [
            'person' => $this->getPersonManager()->find()
        ]);

        return new PdfResponse(
            $snappy->getOutputFromHtml($html, [
                'encoding' => 'utf-8',
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
        return $this->render('front/index.html.twig', [
            'person' => $this->getPersonManager()->find()
        ]);
    }

    /**
     * Part experiences
     * @param Request $request
     * @param bool $pdf
     * @return Response
     */
    public function experiences(Request $request, bool $pdf) : Response
    {
        $orderBy['c.startDate'] = 'desc';
        $locale = $request->getLocale();

        return $this->render(
            'front/parts/experiences.html.twig',
            [
                'pdf' => $pdf,
                'xps' => $this->getContentManager()->getContents(
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
     * @param bool $pdf
     * @return Response
     */
    public function skills(Request $request, bool $pdf) : Response
    {
        $orderBy['c.note'] = 'desc';
        $locale = $request->getLocale();

        return $this->render(
            'front/parts/skills.html.twig',
            [
                'skills' => $this->getContentManager()->getContents(
                    Skill::class,
                    'app.skills.' . Skill::SKILL_TYPE_SKILL . '.' . $locale,
                    ['c.type' => ['=', Skill::SKILL_TYPE_SKILL]],
                    $orderBy
                ),
                'languages' => $this->getContentManager()->getContents(
                    Skill::class,
                    'app.skills.' . Skill::SKILL_TYPE_LANGUAGE . '.' . $locale,
                    ['c.type' => ['=', Skill::SKILL_TYPE_LANGUAGE]],
                    $orderBy
                ),
                'tools' => $this->getContentManager()->getContents(
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
     * @return Response
     */
    public function works(Request $request) : Response
    {
        return $this->render(
            'front/parts/works.html.twig',
            [
                'works' => $this->getContentManager()->getContents(
                    Work::class,
                    'app.works.' . $request->getLocale()
                )
            ]
        );
    }

    public function meta()
    {
        return $this->render('front/pagelayout/page_head.html.twig', [
            'person' => $this->getPersonManager()->find()
        ]);
    }

    private function getContentManager(): ContentManagerInterface
    {
        return $this->contentManager;
    }

    private function getPersonManager(): PersonManagerInterface
    {
        return $this->personManager;
    }
}
