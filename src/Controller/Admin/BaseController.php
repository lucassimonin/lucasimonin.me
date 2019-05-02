<?php

/**
 * Admin controller for manager a module
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class BaseController extends AbstractController
{
    /**
     * @var string
     */
    private $labelList;
    /**
     * @var string|null
     */
    private $routeList;

    /**
     * @param bool $routeList
     *
     * @return Breadcrumbs
     */
    protected function initBreadcrumb(bool $routeList = false): Breadcrumbs
    {
        return $this->getBreadcrumb()->addItem($this->labelList, $routeList && $this->routeList !== null ? $this->getRouter()->generate($this->routeList) : '');
    }

    protected function setFlashBag(string $type, string $message): void
    {
        $this->get('session')->getFlashBag()->set($type, $message);
    }

    protected function sendSuccessFlash(string $message): void
    {
        $this->setFlashBag('success', $message);
    }

    protected function sendErrorFlash(string $message): void
    {
        $this->setFlashBag('error', $message);
    }

    /**
     * @param string $labelList
     */
    public function setLabelList(string $labelList): void
    {
        $this->labelList = $labelList;
    }

    private function getRouter(): RouterInterface
    {
        return $this->get('router');
    }

    private function getBreadcrumb(): Breadcrumbs
    {
        return $this->get('white_october_breadcrumbs')
             ->addItem('admin.dashboard.label', $this->getRouter()->generate('admin_dashboard'));
    }

    /**
     * @param string|null $routeList
     */
    public function setRouteList(?string $routeList): void
    {
        $this->routeList = $routeList;
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'knp_paginator' => '?knp_paginator',
            'white_october_breadcrumbs' => '?white_october_breadcrumbs',
        ]);
    }
}
