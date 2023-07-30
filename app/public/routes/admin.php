<?php

use App\Utils\GetRoutes;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./public/styles/admin.css" />
    <title>Admin</title>
  </head>

  <body>
    <?php require GetRoutes::getPath('/navbar'); ?>

    <div class="Qform" style="overflow-x: auto">
      <form>
        <table>
          <tr>
            <th>Enter Question title :</th>
          </tr>

          <tr>
            <td><textarea id="qtitle" name="title"></textarea></td>
          </tr>
          <tr>
            <th>Enter Question :</th>
          </tr>
          <tr>
            <td><textarea id="q" name="question"></textarea></td>
          </tr>
          <tr>
            <th>Answer 1 :</th>
          </tr>
          <tr>
            <td><textarea id="A1" name="a1"></textarea></td>
          </tr>
          <tr>
            <th>Answer 2 :</th>
          </tr>
          <tr>
            <td><textarea id="A2" name="a2"></textarea></td>
          </tr>
          <tr>
            <th>Answer 3 :</th>
          </tr>
          <tr>
            <td><textarea id="A3" name="a3"></textarea></td>
          </tr>
          <tr>
            <th>Answer 4 :</th>
          </tr>
          <tr>
            <td><textarea id="A4" name="a4"></textarea></td>
          </tr>
          <tr>
            <th>Correct Answer :</th>
          </tr>
          <tr>
            <td>
              <input type="radio" id="Answer1" name="Answer" value="1" /><label
                for="Answer1"
                >Answer 1</label
              ><br />
              <input type="radio" id="Answer2" name="Answer" value="2" /><label
                for="Answer2"
                >Answer 2</label
              ><br />
              <input type="radio" id="Answer3" name="Answer" value="3" /><label
                for="Answer3"
                >Answer 3</label
              ><br />
              <input type="radio" id="Answer4" name="Answer" value="4" /><label
                for="Answer4"
                >Answer 4</label
              ><br />
            </td>
          </tr>
          <tr id="raw2">
            <td>
              <input type="reset" id="button4" value="Reset" />&nbsp<input
                type="submit"
                id="submit"
                value="Submit"
              />
            </td>
          </tr>
        </table>
      </form>
    </div>
  </body>

  <script src="./public/js/admin.js"></script>
</html>
