<?php


use App\Connnections\DB;
use App\Models\User;
use App\Utils\GetRoutes;
use App\Utils\Token;


use App\Middleware\Auth;
use App\Models\Question;

use App\Connnections\RedisDB;

$today = RedisDB::connect()->get('today');
?>

<?php if($today == "") : ?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <title>Question</title>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
      <?php include GetRoutes::getPath('/navbar'); ?>
      <main class="flex flex-col justify-center items-center min-h-screen">
        <h1 class="text-4xl font-bold">No question for today</h1>
        <p class="mt-4 text-gray-400">Please come back tommorow ...</p>
      </main>
    </body>
  </html>



<?php else: ?>
    <?php
      $question = new Question;
      $question->fetchTodaysQuestion();
    ?>

    <!DOCTYPE html>
    <html lang="en">
      <head>
        <title>Question</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="./public/styles/question.css" />
      </head>

      <body class="flex flex-col items-center justify-center min-h-screen bg-[#FBFBFB] mt-16">
        <?php include GetRoutes::getPath('/navbar'); ?>
        <main class="container py-8 mx-auto bg-[#FBFBFB]">
          <div class="p-16 mx-auto max-w-7xl bg-white rounded-lg shadow">
            <h1 class="mb-4 text-3xl font-bold">
              <?php echo $question->getTitle(); ?>
            </h1>
            <p class="text-lg">
              <?php echo $question->getQuestion(); ?>
            </p>

            <form class="my-6 w-[75%]">
              <h2 class="mb-2 text-xl font-bold">Choose one answer:</h2>
              <div class="flex flex-col space-y-2 sm:space-y-4">
                    <?php foreach($question->getAnswers() as $index=>$answer): ?>
                  <label class="answer-option">
                    <input type="radio" name="answer" value=<?php echo $index; ?> class="hidden" />
                    <span class="checkmark"></span>
                    <span class="option-text"><?php echo $answer; ?></span>
                  </label>
                    <?php endforeach; ?>
              </div>

              <?php if(Auth::isAuthed()) : ?>
                    <?php 
                    Token::decode($_COOKIE['session']);

                    $user = new User;
                    $user->fetchUser(username: Token::$sub);

                    $db = DB::db();
                    $stmt = $db->prepare("SELECT id FROM answers WHERE user_id=:user_id AND question_id=:question_id");
                    $stmt->execute(
                        [
                        ':user_id' => $user->getID(),
                        ':question_id' => $today
                        ]
                    );
                    $result = $stmt->fetch();
                    ?>
                    <?php if($result) : ?>
                        <button
                          id="leaderboard"
                          type="button"
                          class="py-3 px-6 mt-6 w-full font-semibold text-white bg-[#0DCBF6] rounded-lg shadow-md transition-colors hover:bg-[#00C9F6]"
                        >
                        <a href="/leaderboard">Already submited, Check the leaderboard !</a>
                        </button>
                    <?php else: ?>
                        <button
                          id="submit"
                          type="submit"
                          class="py-3 px-6 mt-6 w-full font-semibold text-white bg-[#0DCBF6] rounded-lg shadow-md transition-colors hover:bg-[#00C9F6]"
                        >
                          Submit
                        </button>
                    <?php endif; ?>
              <?php else: ?>
                <button
                  type="button"
                  class="py-3 px-6 mt-6 w-full font-semibold text-white bg-[#0DCBF6] rounded-lg shadow-md transition-colors hover:bg-[#00C9F6]"
                >
                  <a href="/login">Continue with GitHub</a>
                </button>
              <?php endif; ?>

            </form>

          </div>
        </main>
      </body>
      <script src="./public/js/question.js"></script>
    </html>

<?php endif; ?>
