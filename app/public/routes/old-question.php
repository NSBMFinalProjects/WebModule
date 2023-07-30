<?php
use App\Utils\GetRoutes;
use App\Models\Question;

$id = $_GET['id'];
?>

<?php
$question = new Question;
try {
    $question->fetchQuestion($id);
} catch (Exception $e) {
    echo '<script>window.location.href="/past-questions"</script>';
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Old Question | <?php echo $question->getTitle(); ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./public/styles/question.css" />
  </head>

  <body class="flex flex-col items-center justify-center min-h-screen bg-[#FBFBFB]">
    <?php require GetRoutes::getPath('/navbar'); ?>
    <main class="container py-8 mx-auto bg-[#FBFBFB]">
      <div class="p-16 mx-auto max-w-7xl bg-white rounded-lg shadow">
        <h1 class="mb-4 text-3xl font-bold">
          <?php echo $question->getTitle(); ?>
        </h1>
        <p class="text-lg">
          <?php echo $question->getQuestion(); ?>
        </p>
      </div>
    </main>
  </body>
</html>
