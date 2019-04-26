<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class Builder
{
    /** @var FactoryInterface  */
    private $factory;

    /** @var ItemInterface */
    private $menu;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createAdminMenu(): ItemInterface
    {
        $this->menu = $this->factory->createItem('root');
        $this->addChild('dashboard', 'admin_dashboard', 'admin.dashboard.label', 'fa fa-tachometer')
            ->addChild('experience', 'admin_experience_list', 'admin.experience.menu.label', 'fa fa-address-book-o')
            ->addChild('work', 'admin_work_list', 'admin.work.menu.label', 'fa fa-suitcase')
            ->addChild('skill', 'admin_skill_list', 'admin.skill.menu.label', 'fa fa-id-card-o')
            ->addChild('users', 'admin_user_list', 'admin.users.menu.label', 'fa fa-users');

        return $this->menu;
    }

    private function addChild(string $id, string $route, string $label, ?string $icon): self
    {
        $this->menu->addChild($id, ['route' => $route])
             ->setLabel($label)
             ->setLabelAttribute('icon', $icon)
        ;

        return $this;
    }
}
