<?php

namespace Remg\UserBundle\Menu;

use Knp\Menu\MenuItem;
use Remg\LayoutBundle\Menu\AbstractMenuBuilder;

class MenuBuilder extends AbstractMenuBuilder
{
    public function buildMainMenu(MenuItem $menu)
    {
        // Node "Users"
        $node = $menu->addChild('Users');
        $node->setAttribute('icon', 'fa fa-users');

        // Child "Index"
        $node->addChild('Index', ['route' => 'admin_user_index']);
        // Child "New"
        $node->addChild('New', ['route' => 'admin_user_new']);
        // Child "Show"
        $node
            ->addChild('Show', [
                'route' => 'admin_user_show',
                'routeParameters' => ['id' => $this->request->get('id', 0)],
            ])
            ->setDisplay(false);
        // Child "Edit"
        $node
            ->addChild('Edit', [
                'route' => 'admin_user_edit',
                'routeParameters' => ['id' => $this->request->get('id', 0)],
            ])
            ->setDisplay(false);
    }
}