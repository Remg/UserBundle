<?php

namespace Remg\UserBundle\EventListener;

use Remg\LayoutBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \AppBundle\Event\ConfigureMenuEvent $event
     */
    public function onAdminMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        $request = $event->getRequest();


        // Node "Users"
        $node = $menu->addChild('user.plural');
        $node->setAttribute('icon', 'fa fa-user');

        // Child "Index"
        $node->addChild('index', ['route' => 'admin_user_index']);
        // Child "New"
        $node->addChild('create', ['route' => 'admin_user_new']);
        // Child "Show"
        $node
            ->addChild('view', [
                'route' => 'admin_user_show',
                'routeParameters' => ['id' => $request->get('id', 0)],
            ])
            ->setDisplay(false);
        // Child "Edit"
        $node
            ->addChild('edit', [
                'route' => 'admin_user_edit',
                'routeParameters' => ['id' => $request->get('id', 0)],
            ])
            ->setDisplay(false);


        // Node "Groups"
        $node = $menu->addChild('group.plural');
        $node->setAttribute('icon', 'fa fa-group');

        // Child "Index"
        $node->addChild('index', ['route' => 'admin_group_index']);
        // Child "New"
        $node->addChild('create', ['route' => 'admin_group_new']);
        // Child "Show"
        $node
            ->addChild('view', [
                'route' => 'admin_group_show',
                'routeParameters' => ['id' => $request->get('id', 0)],
            ])
            ->setDisplay(false);
        // Child "Edit"
        $node
            ->addChild('edit', [
                'route' => 'admin_group_edit',
                'routeParameters' => ['id' => $request->get('id', 0)],
            ])
            ->setDisplay(false);
    }
}