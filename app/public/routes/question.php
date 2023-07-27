<?php

use App\Connnections\RedisDB;
use App\Models\Question;

$redis = RedisDB::connect();
$question = new Question();

$today = $redis->get('today');
if ($today != "") {
    try {
        $question->markQuestionAsDisplayed($today);
    } catch (Exception $e) {
        echo "Something went wrong";
        return;
    }
}

try {
    $question->fetchTodaysQuestion();
    /* $redis->set('today', $question->getID()); */
} catch (Exception $e) {
    echo "Something went wrong";
    return;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question page</title>
</head>
<body>
    <h1>
        <?php echo $question->getQuestion() ?>
    </h1>

    <ul>
        <?php
        foreach ($question->getAnswers() as $answer) {
            echo '<li>' . $answer . '</li>';
        }
        ?>
    </ul>
</body>

</html>
