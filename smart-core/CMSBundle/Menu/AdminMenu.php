<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class AdminMenu
{
    protected FactoryInterface $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
//        $this->kernel  = $kernel;
    }

    public function main(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('cms_admin_main');

        $menu->setChildrenAttribute('class', isset($options['class']) ? $options['class'] : 'nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact nav-legacy');
        $menu->setChildrenAttribute('data-accordion', 'false');
        $menu->setChildrenAttribute('data-widget', 'treeview');
        $menu->setChildrenAttribute('role', 'menu');

        $menu->addChild('Control panel')
            ->setAttribute('class', 'nav-header');

        $menu->addChild('Dashboard', ['route' => 'cms_admin.index'])
            ->setExtras(['icon' => 'fas fa-tachometer-alt'])
            ->setAttribute('class', 'nav-item')
        ;

        $menu->addChild('Datasets', ['route' => 'cms_admin.dataset'])
            ->setExtras(['icon' => 'fas fa-th'])
            ->setAttribute('class', 'nav-item')
        ;

        return $menu;
    }
}
