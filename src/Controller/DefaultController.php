<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/{vueRouting}", requirements={"vueRouting"="^(?!api|_(profiler|wdt)).*"}, name="index", options={"no_auth": true})
     */
    public function indexAction(RouterInterface $router) : Response
    {
        $routes = [];
        foreach ($router->getRouteCollection()->all() as $name => $route) {
            if ($route->getPath()[0] === '_' || $name[0] === '_') {
                continue;
            }

            $routes[] = [
                'name' => $name,
                'path' => $route->getPath(),
                'meta' => [
                    'auth' => (boolean)$route->getOption('no_auth')
                ]
            ];
        }

        return $this->render('base.html.twig', ['routes' => $routes, 'resetRoutes' => false]);
    }
}
