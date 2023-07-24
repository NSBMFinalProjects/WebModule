<?php
namespace App\Models;

use App\Errors\General\InternalServerError;
use Exception;
use Fuel\Validation\ResultInterface;
use Fuel\Validation\Validator;
use App\Connnections\DB;
use App\Errors\DB\ConnectionError;
use PDO;

class Question
{
    private $id = null;
    private $docID;
    private $attempts = 0;
    private $correct = 0;

    private PDO $db;

    public function __construct()
    {
        $this->db = DB::db();
        if (!$this->db) {
            throw new ConnectionError();
        }
    }

    /**
     * Check wether the data have validation errors
     *
     * @return ResultInterface
     **/
    public static function validate(mixed $data): ResultInterface
    {
        $v = new Validator;

        $v
            ->addField("question", "question")

            ->required()
            ->minLength(20)
            ->maxLength(600)

            ->addField("1", "1")

            ->required()
            ->minLength(4)
            ->maxLength(200)

            ->addField("2", "2")

            ->required()
            ->minLength(4)
            ->maxLength(200)

            ->addField("3", "3")

            ->required()
            ->minLength(4)
            ->maxLength(200)

            ->addField("4", "4")

            ->required()
            ->minLength(4)
            ->maxLength(200);

        $result = $v->run($data);
        return $result;
    }

    /**
     * Create a new question in the database
     *
     * @param string docID The document ID of the question in the mongodb database
     **/
    public function create(string $docID): void
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO questions (doc_id) VALUES (:doc_id)");
            $stmt->execute(
                [
                ':doc_id' => $docID
                ]
            );
            $stmt->fetch();
        } catch (Exception $e) {
            throw new InternalServerError(message: $e->getMessage());
        }
    }

    /**
     * Get the ID of the Question
     *
     * @return string | null
     **/
    public function getID(): string | null
    {
        return $this->id;
    }

    /**
     * Get the MongoDB document ID of the Question
     *
     * @return string
     **/
    public function getDocID(): string
    {
        return $this->docID;
    }

    /**
     * Get the number of times that the users have attempted the question
     *
     * @return int
     **/
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * Get the number of times that the user have got the question correct
     *
     * @return int
     **/
    public function getCorrect(): string
    {
        return $this->correct;
    }

}
