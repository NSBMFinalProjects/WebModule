<?php

use App\Connnections\DB;

$db = DB::db();
$stmt = $db->prepare(
    "SELECT users.id, users.display_name, users.username, users.photo_url, answers.* FROM answers INNER JOIN users ON users.id = answers.user_id WHERE answers.is_correct = TRUE ORDER BY answers.answer_delay ASC LIMIT 10"
);
$stmt->execute([]);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Leaderboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <main
      class="flex flex-col justify-center items-center min-h-screen bg-[#FEF8F8]"
    >
      <div
        class="container pt-20 pr-16 pb-20 pl-16 mx-auto bg-white border-2 border-white border-solid w-[700px]"
      >
        <h1 class="mb-16 text-4xl font-bold">Leaderboard</h1>
        <table
          style="border-collapse: separate; border-spacing: 1em"
          class="w-full table-auto"
        >
          <thead class="bg-[#0DCBF6]">
            <tr>
              <th class="text-left text-white">Rank</th>
              <th class="text-left text-white">Name</th>
            </tr>
          </thead>
          <tbody class="">
            <?php foreach ($results as $index=>$result) : ?>
              <tr class="mt-16">
              <td class="text-left"><?php echo $index + 1; ?></td>
                <td class="text-left"><?php echo $result['display_name'] . ' (' . $result['username'] . ')' ; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </body>
</html>
