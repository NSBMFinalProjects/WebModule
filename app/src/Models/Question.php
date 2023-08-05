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
    private $title;
    private $attempts = 0;
    private $correct = 0;
    private $answer;
    private $displayed;
    private $question;
    private $answers;
    private $displayed_date;

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
            ->addField("title", "title")

            ->required()
            ->minLength(5)
            ->maxLength(1000)

            ->addField("question", "question")

            ->required()
            ->minLength(20)
            ->maxLength(1000)

            ->addField("1", "1")

            ->required()
            ->minLength(4)
            ->maxLength(1000)

            ->addField("2", "2")

            ->required()
            ->minLength(4)
            ->maxLength(1000)

            ->addField("3", "3")

            ->required()
            ->minLength(4)
            ->maxLength(1000)

            ->addField("4", "4")

            ->required()
            ->minLength(4)
            ->maxLength(1000)

            ->addField("answer", "answer")

            ->required()
            ->number();
          

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
              'title' => $data['title'],
              'question' => $data['question'],
              '1' => $data['1'],
              '2' => $data['2'],
              '3' => $data['3'],
              '4' => $data['4']
            );

            $docID = $mongo->selectCollection(MongoCollections::QUESTIONS->value)->insertOne($document)->getInsertedId();

            $stmt = $this->db->prepare("INSERT INTO questions (title, doc_id, answer) VALUES (:title, :doc_id, :answer)");
            $stmt->execute(
                [
                  ':title' => $data['title'],
                  ':doc_id' => $docID,
                  ':answer' => $data['answer']
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
            $this->title = $questionMetadata['title'];
            $this->attempts = $questionMetadata['attempts'];
            $this->correct = $questionMetadata['correct'];
            $this->displayed = $questionMetadata['attempts'];
            $this->answer = $questionMetadata['answer'];
            $this->displayed_date = $questionMetadata['displayed_date'];

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
            $this->title = $questionMetadata['title'];
            $this->attempts = $questionMetadata['attempts'];
            $this->correct = $questionMetadata['correct'];
            $this->displayed = $questionMetadata['attempts'];
            $this->answer = $questionMetadata['answer'];
            $this->displayed_date = $questionMetadata['displayed_date'];

            if (!$this->displayed_date) {
                $this->displayed_date = time();
                try {
                    $stmt = $this->db->prepare("UPDATE questions SET displayed_date=:date WHERE id=:id");
                    $stmt->execute(
                        [
                        ':id' => $this->id,
                        ':date' => $this->displayed_date,
                        ]
                    );
                    $stmt->fetch();
                } catch (Exception $e) {
                    throw new InternalServerError(message: $e->getMessage());
                }
            }

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
            $stmt = $this->db->prepare('UPDATE questions SET displayed = true WHERE id=?');
            $stmt->execute([$id]);
            $stmt->fetch();
        } catch (Exception $e) {
            throw new InternalServerError(message: $e->getMessage());
        }
    }

    /**
     * Get the title of the question
     *
     * @return string
     **/
    public function getTitle(): string
    {
        return $this->title;
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
     * Get the correct answer for the given question
     *
     * @return int
     **/
    public function getCorectAnswer(): int
    {
        return $this->answer;
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

    /**
     * Get the dispalyed date of the question
     *
     * @return int
     **/
    public function getDisplayedDate(): int
    {
        return $this->displayed_date;
    }
}
