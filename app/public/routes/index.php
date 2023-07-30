<?php

use App\Utils\GetRoutes;

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>IT Questions</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="w-full">
    <?php require GetRoutes::getPath('/navbar'); ?>
    <main class="w-full bg-red-400 bg-cover bg-center min-h-screen bg-[url('./public/hero.png')] flex mt-24">
    <div class="flex flex-col w-full min-h-screen">
      <div class="flex w-full">
        <div class="flex flex-col justify-center items-center p-10 w-1/2 shadow-lg"> 
          <div class="rounded-2xl bg-[#000E8B]/60 p-10 flex flex-col items-center justify-center">
            <p class="w-full text-7xl font-extrabold text-white">Get started with <br/><span class="text-[#D7F909]">Daily Quiz</span></p>
            <p class="mt-10 w-3/4 text-5xl font-bold text-center text-white drop-shadow">Expand Your programming  Knowledge with Daily Challenges!</p>
          </div>
          <a href='/question'>
            <div class="w-3/4 rounded-xl bg-[#70F00C] text-4xl font-bold drop-shadow py-4 px-5 mt-10 shadow-lg">Take Today's Questions</div>
          </a>
        </div>
        <div class="flex flex-col justify-center items-center p-10 w-1/2">
          <img src='./public/assets/hero2.png'/>
            <div class="flex -mt-28">
          <img src='./public/assets/hero3.png'/>
          <img src='./public/assets/hero4.png' width="320px"/>
            </div>
        </div>
      </div>
    </div>
    </main>
  </body>
</html>
