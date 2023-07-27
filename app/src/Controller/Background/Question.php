<?php
namespace App\Controller\Background;

use App\Connnections\RedisDB;
use App\Models\Question as AppQuestion;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Question extends AbstractController
{
    #[Route(methods: ['GET'])]
    public function getNewQuestion(Request $req): Response
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

        $code = $req->query->get("code");
        if (!$code || $code != $_ENV['ROUTE_SECRET']) {
            return new Response(
                'unauthorized',
                Response::HTTP_UNAUTHORIZED,
                [
                'content-type' => 'application/json'
                ]
            );
        }

        $redis = RedisDB::connect();
        $question = new AppQuestion();

        $today = $redis->get('today');
        if ($today != "") {
            try {
                $question->markQuestionAsDisplayed($today);
            } catch (Exception $e) {
            }
        }

        try {
            $question->fetchTodaysQuestion();
            $id = $question->getID();
            $redis->set('today', $id);
        } catch (Exception $e) {
            return new Response(
                $e->getMessage(),
                Response::HTTP_OK,
                [
                'content-type' => 'application/json'
                ]
            );
        }

        return new Response(
            $question->getID(),
            Response::HTTP_OK,
            [
            'content-type' => 'application/json'
            ]
        );
    }
}
