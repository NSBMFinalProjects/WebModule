<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\GetRoutes;

class General extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        // TODO: Check wehter the user is authenticated or not before making showing the home page to the user
        // if the user is authenticated then show him the questions page otherwise show him the promotional page
        $is_authenticated = true;

        if ($is_authenticated) {
            include GetRoutes::getPath('/question');
        } else {
            include GetRoutes::getPath();
        }

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        include GetRoutes::getPath('/contact');
        return new Response(null, Response::HTTP_OK);
    }
}
