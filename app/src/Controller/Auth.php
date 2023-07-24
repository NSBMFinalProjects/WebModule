<?php
namespace App\Controller;

use App\Middleware\Auth as AppAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Auth extends AbstractController
{
    public function logout(Request $req): Response
    {
        if (!$req->isMethod(Request::METHOD_GET)) {
            return new Response(
                'method not allowed',
                Response::HTTP_BAD_REQUEST,
                [
                'content-type' => 'application/json'
                ]
            );
        }

        $state = $req->query->get("state");
        if ($state == "") {
            $state = "home";
        }

        if (!AppAuth::isAuthed()) {
            return $this->redirectToRoute($state);
        }


        $res = new Response(null, status: Response::HTTP_OK);
        $res->headers->clearCookie('session', '/', 'localhost');
        $res->sendHeaders();

        return $this->redirectToRoute($state);
    }
}
