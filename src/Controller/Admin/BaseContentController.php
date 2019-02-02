<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use App\Model\SearchContent;
use App\Form\Type\Content\SearchContentType;
use App\Services\Content\ContentManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseContentController extends AbstractController
{
    /**
     * @var ContentManagerInterface
     */
    private $contentManager;

    /**
     * SkillController constructor.
     * @param ContentManagerInterface $contentManager
     */
    public function __construct(ContentManagerInterface $contentManager)
    {
        $this->contentManager = $contentManager;
    }

    /**
     * @param Request $request
     * @param string $class
     * @return array
     */
    protected function initSearch(Request $request, string $class): array
    {
        $search = new SearchContent($request->query->all());
        $form = $this->createForm(SearchContentType::class, $search);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getRepository($class)->queryForSearch($search),
            $search->getPage(),
            SearchContent::$limit
        );

        return [$pagination, $form];
    }

    protected function getContentManager(): ContentManagerInterface
    {
        return $this->contentManager;
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'knp_paginator' => '?knp_paginator',
            'white_october_breadcrumbs' => '?white_october_breadcrumbs',
        ]);
    }
}
