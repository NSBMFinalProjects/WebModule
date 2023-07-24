<?php
namespace App\Controller;

use App\Connnections\Mongo;
use App\Models\Question as AppQuestion;
use Exception;
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
                json_encode($result->getErrors()),
                Response::HTTP_BAD_REQUEST,
                [
                'content-type' => 'application/json'
                ]
            );
        }

        $mongo = Mongo::db();
        if (!$mongo) {
            return new Response(
                'internal server error',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [
                'content-type' => 'application/json'
                ]
            );
        }

        $document = array(
          'question' => $body['question'],
          '1' => $body['1'],
          '2' => $body['2'],
          '3' => $body['3'],
          '4' => $body['4']
        );

        try {
            $docID = $mongo->selectCollection('questions')->insertOne($document)->getInsertedId();
            $newQuestion = new AppQuestion;
            $newQuestion->create($docID);
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
}
