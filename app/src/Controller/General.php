<?php
namespace App\Controller;

use App\Middleware\Auth;
use Exception;
use Resend;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\GetRoutes;
use Fuel\Validation\Validator;

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
    public function contact(Request $req): Response
    {
        if ($req->isMethod(Request::METHOD_POST)) {
            $body = json_decode($req->getContent(), true);

            try {
                $v = new Validator;
                $v
                    ->addField("name", "name")->minLength("3")->maxLength("50")->required()
                    ->addField("email", "email")->email()->required()
                    ->addField("message", "message")->minLength(5)->maxLength(500)->required();

                $result = $v->run($body);
                if (!$result->isValid()) {
                    return new Response(
                        json_encode(['errors' => $result->getErrors()]),
                        Response::HTTP_BAD_REQUEST,
                        ['content-type' => 'application/json']
                    );
                }

                $name = $body['name'];
                $email = $body['email'];
                $message = $body['message'];

                $content = <<<EOD
                <h1> $name Wants you to respond </h1>
                <ul>
                  <li> Name: $name </li>
                  <li> Email: <a href="mailto:$email">$email</a> </li>
                </ul>
                <h2>Message</h2>
                <p>$message</p>
                EOD;

                $resend = Resend::client($_ENV['RESEND_API']);
                $resend->emails->send(
                    [
                      'from' => 'Contact us <webmodule@mail.vinuka.me>',
                      'to' => ['vtkodituwakku@students.nsbm.ac.lk'],
                      'subject' => "Contact us submission from " . $name,
                      'html' => $content
                    ]
                );

            } catch (Exception $e) {
                return new Response(
                    json_encode(['status' => $e->getMessage()]),
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['content-type' => 'application/json']
                );
            }

            return new Response(
                json_encode(['status' => 'okay']),
                Response::HTTP_OK,
                ['content-type' => 'application/json']
            );
        }

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
