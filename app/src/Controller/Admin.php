<?php
namespace App\Controller;

use App\Middleware\Auth;
use App\Utils\GetRoutes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Admin extends AbstractController
{
    #[Route(name: 'admin')]
    public function admin(Request $req): Response
    {
        if (!Auth::isAdmin()) {
            return $this->redirectToRoute(route: 'home');
        }

        include GetRoutes::getPath('/admin');
        return new Response();
    }
}
