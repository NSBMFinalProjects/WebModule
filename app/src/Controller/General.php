<?php
namespace App\Controller;

use App\Middleware\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\GetRoutes;

class General extends AbstractController
{
    #[Route(name: 'home')]
    public function home(): Response
    {
        $is_authenticated = Auth::isAuthed();

        if ($is_authenticated) {
            include GetRoutes::getPath('/question');
        } else {
            include GetRoutes::getPath();
        }

        return new Response(null, Response::HTTP_OK);
    }

    #[Route(name: 'contact')]
    public function contact(): Response
    {
        include GetRoutes::getPath('/contact');
        return new Response(null, Response::HTTP_OK);
    }

    #[Route(name: 'question')]
    public function question(): Response
    {
        include GetRoutes::getPath('/question');
        return new Response(null, Response::HTTP_OK);
    }
}
