<?php
namespace App\Controller;

use App\Connnections\DB;
use App\Connnections\RedisDB;
use App\Middleware\Auth;
use App\Models\Question as AppQuestion;
use App\Models\User;
use App\Utils\Token;
use Exception;
use Fuel\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Question extends AbstractController
{
    #[Route(name: 'create-question')]
    public function create(Request $req): Response
    {
       
        if (!$req->isMethod(Request::METHOD_POST)) {
            return new Response(
                'method not allowed',
                Response::HTTP_METHOD_NOT_ALLOWED,
                [
                  'content-type' => 'application/json'
                ]
            );
        }
        $body = json_decode($req->getContent(), true);

        $result = AppQuestion::validate($body);
        if (!$result->isValid()) {
            return new Response(
                'bad request',
                Response::HTTP_BAD_REQUEST,
                [
                  'content-type' => 'application/json'
                ]
            );
        }

        try {
            $newQuestion = new AppQuestion;
            $newQuestion->create($body);
        } catch(Exception $e) {
            return new Response(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [
                  'content-type' => 'application/json'
                ]
            );
        }

        return new Response(
            'success',
            Response::HTTP_OK,
            [
              'content-type' => 'application/json'
            ]
        );
    }

    #[Route(name: 'answer-question')]
    public function answer(Request $req): Response
    {
        $now = time();

        if (!$req->isMethod(Request::METHOD_POST)) {
            return new Response(
                'method not allowed',
                Response::HTTP_METHOD_NOT_ALLOWED,
                [
                'content-type' => 'application/json' 
                ]
            );
        }

        if (!Auth::isAuthed()) {
            return new Response(
                'unauthorized',
                Response::HTTP_UNAUTHORIZED,
                [
                'content-type' => 'application/json'
                ]
            );
        }

        $body = json_decode($req->getContent(), true);

        $v = new Validator;
        $v->addField('answer', 'answer')->required()->number();
        $result = $v->run($body);
        if (!$result->isValid()) {
            return new Response(
                'bad Request',
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        try {
            $redis = RedisDB::connect();
            $db = DB::db();

            $today = $redis->get("today");
            if ($today == "") {
                return new Response(
                    'bad request',
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            }

            Token::decode($_COOKIE['session']);

            $user = new User;
            $question = new AppQuestion;

            $user->fetchUser(username: Token::$sub);
            $question->fetchQuestion($today);

            $stmt = $db->prepare("SELECT id FROM answers WHERE user_id=:user_id AND question_id=:question_id");
            $stmt->execute(
                [
                ':user_id' => $user->getID(),
                ':question_id' => $today
                ]
            );
            $result = $stmt->fetch();
            if ($result) {
                return new Response(
                    'already_answered',
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            }

            $isCorrect = false;
            if ($question->getCorectAnswer() == $body['answer']) {
                $isCorrect = true;
            }
            $delay = $now - $question->getDisplayedDate();
            if ($delay <= 0 || $delay > 86400) {
                return new Response(
                    'timeout',
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            }

            $stmt = $db->prepare("INSERT INTO answers (user_id, question_id, answer, answer_delay, is_correct) VALUES (:user_id, :question_id, :answer, :answer_delay, :is_correct)");
            $stmt->execute(
                [
                ':user_id' => $user->getID(),
                ':question_id' => $question->getID(),
                ':answer' => $body['answer'],
                ':answer_delay' => $delay,
                ':is_correct' => $isCorrect ? "YES" : "NO"
                ]
            );
            $stmt->fetch();

        } catch(Exception $e) {
            return new Response(
                json_encode(
                    [
                    'error' => $e->getMessage(),
                    ]
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['content-type' => 'application/json']
            );
        }

        return new Response(
            json_encode(['is_correct' => $isCorrect ]),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
