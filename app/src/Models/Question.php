<?php
namespace App\Models;

use App\Connnections\Mongo;
use App\Enums\MongoCollections;
use App\Errors\General\BadRequest;
use App\Errors\General\InternalServerError;
use Exception;
use Fuel\Validation\ResultInterface;
use Fuel\Validation\Validator;
use App\Connnections\DB;
use App\Errors\DB\ConnectionError;
use PDO;
use MongoDB\BSON\ObjectId;

class Question
{
    private $id = null;
    private $docID;
    private $attempts = 0;
    private $correct = 0;
    private $displayed;
    private $question;
    private $answers;

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
     * @param mixed data The data that must be contained with the question
     **/
    public function create(mixed $data): void
    {
        try {
            $mongo = Mongo::db();
            if (!$mongo) {
                throw new InternalServerError;
            }

            $document = array(

            'question' => $data['question'],
            '1' => $data['1'],
            '2' => $data['2'],
            '3' => $data['3'],
            '4' => $data['4']
            );

            $docID = $mongo->selectCollection(MongoCollections::QUESTIONS->value)->insertOne($document)->getInsertedId();

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
     * Get the question with the given ID from the database
     *
     * @param string id The Question ID
     **/
    public function fetchQuestion(string $id): void
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM questions WHERE id=?");
            $stmt->execute([$id]);
            $questionMetadata = $stmt->fetch();
            if (!$questionMetadata) {
                throw new BadRequest(message: "Question with the given ID is not found");
            }

            $this->id = $id;
            $this->docID = $questionMetadata['doc_id'];
            $this->attempts = $questionMetadata['attempts'];
            $this->correct = $questionMetadata['correct'];
            $this->displayed = $questionMetadata['attempts'];

            $mongo = Mongo::db();
            if (!$mongo) {
                throw new InternalServerError(message: "connection to mongodb failed");
            }

            $doc = $mongo->selectCollection(MongoCollections::QUESTIONS->value)->findOne(
                array(
                '_id' => new ObjectId($this->docID)
                )
            );

            try {
                $this->question = $doc['question'];
                $this->answers = array(
                    '1' => $doc['1'],
                    '2' => $doc['2'],
                    '3' => $doc['3'],
                    '4' => $doc['4']
                );
            } catch (Exception $e) {
                throw new InternalServerError(message: "Failed to connect with the database");
            }
        } catch(Exception $e) {
            throw new InternalServerError(message: $e->getMessage());
        }
    }

    /**
     * Fetch the question that must be fetched today
     **/
    public function fetchTodaysQuestion(): void
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM questions WHERE displayed = false ORDER BY created_at LIMIT 1");
            $stmt->execute();
            $questionMetadata = $stmt->fetch();
            if (!$questionMetadata) {
                throw new BadRequest("No questions to show");
            }

            $this->id = $questionMetadata['id'];
            $this->docID = $questionMetadata['doc_id'];
            $this->attempts = $questionMetadata['attempts'];
            $this->correct = $questionMetadata['correct'];
            $this->displayed = $questionMetadata['attempts'];

            $mongo = Mongo::db();
            if (!$mongo) {
                throw new InternalServerError(message: "connection to mongodb failed");
            }

            $doc = $mongo->selectCollection(MongoCollections::QUESTIONS->value)->findOne(
                array(
                '_id' => new ObjectId($this->docID)
                )
            );

            try {
                $this->question = $doc['question'];
                $this->answers = array(
                    '1' => $doc['1'],
                    '2' => $doc['2'],
                    '3' => $doc['3'],
                    '4' => $doc['4']
                );
            } catch (Exception $e) {
                throw new InternalServerError(message: "Failed to connect with the database");
            }
        } catch(Exception $e) {
            if ($e->getCode() == 400) {
                throw new BadRequest(message: $e->getMessage());
            }

            throw new InternalServerError(message: $e->getMessage());
        }
    }

    /**
     * Mark the given question as displayed in the database
     *
     * @param string id The ID of the question that should be marked as displayed
     **/
    public function markQuestionAsDisplayed(string $id): void
    {
        try {
            $stmt = $this->db->prepare('update questions set displayed = true where id=?');
            $stmt->execute([$id]);
            $stmt->fetch();
        } catch (Exception $e) {
            throw new InternalServerError(message: $e->getMessage());
        }
    }

    /**
     * Get the questions from the questions relational
     *
     * @return string
     **/
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * Get the answers of the fetched question
     *
     * @return array
     **/
    public function getAnswers(): array
    {
        return $this->answers;
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

    /**
     * Check wether a given question is previously displayed or not
     *
     * @return bool
     **/
    public function getDisplayed(): bool
    {
        return $this->displayed;
    }

}
