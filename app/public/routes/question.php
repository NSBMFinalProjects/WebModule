<?php

use App\Models\Question;

$question = new Question();
try {
    $question->fetchTodaysQuestion();
    echo $question->getQuestion();
} catch(Exception $e)  {
    echo "No questions to show";
}

echo "question page";
