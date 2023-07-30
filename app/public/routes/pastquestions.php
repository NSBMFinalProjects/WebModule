<?php

use App\Utils\GetRoutes;
use App\Connnections\DB;

$db = DB::db();
$stmt = $db->prepare(
    "SELECT * FROM questions WHERE displayed = TRUE ORDER BY displayed_date DESC"
);
$stmt->execute([]);
$results = $stmt->fetchAll();

date_default_timezone_set('Asia/Colombo');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./public/styles/pastquestions.css" />
    <title>Past Questions</title>
    <link rel="icon" type="image/x-icon" href="group/24.webp" />
  </head>

  <body>
    <?php require GetRoutes::getPath('/navbar'); ?>
    <div class="oldq">
      <h1>Past Questions</h1>

      <?php foreach ($results as $index=>$result) : ?>
            <?php
            $shownDate = date('Y-m-d', $result['displayed_date']);
            ?>

              <a href=<?php echo '/past-questions?id=' . $result['id']; ?>>
              <button type="button" class="row">
              <span class="text" id="t1"><?php echo $result['title']; ?></span>
              <span class="date" id="d1"><?php echo $shownDate; ?></span>
              </button> </a
            ><br />
      <?php endforeach; ?>
    </div>
  </body>
</html>
