<?php
namespace App\Controller;

use App\Middleware\Auth as AppAuth;
use App\Models\User;
use App\Utils\Token;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Auth extends AbstractController
{
    #[Route(methods: ['GET'])]
    public function me(Request $req): Response
    {
        if (!$req->isMethod(Request::METHOD_GET)) {
            return new Response(
                json_encode([]),
                Response::HTTP_METHOD_NOT_ALLOWED,
                [
                'content-type' => 'application/json'
                ]
            );
        }

        if (!AppAuth::isAuthed()) {
            return new Response(
                json_encode([]),
                Response::HTTP_UNAUTHORIZED,
                [
                'content-type' => 'application/json'
                ]
            );
        }

        try {
            Token::decode($_COOKIE['session']);

            $user = new User;
            $user->fetchUser(username: Token::$sub);

            return new Response(
                json_encode(
                    array(
                    'id' => $user->getID(),
                    'username' => $user->getUsername(),
                    'photo_url' => $user->getPhotoURL(),
                    'display_name' => $user->getDisplayName(),
                    'email' => $user->getEmail()
                    )
                ),
                Response::HTTP_OK,
                [
                'content-type' => 'application/json'
                ]
            );
        } catch (Exception $e) {
            return new Response(
                json_encode([]),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [
                'content-type' => 'application/json'
                ]
            );
        }
    }
  
    #[Route(methods: ['GET'])]
    public function logout(Request $req): Response
    {
        if (!$req->isMethod(Request::METHOD_GET)) {
            return new Response(
                'method not allowed',
                Response::HTTP_METHOD_NOT_ALLOWED,
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
        $res->headers->clearCookie('session', '/', $_ENV['DOMAIN']);
        $res->sendHeaders();

        return $this->redirectToRoute($state);
    }
}
