<?php


use App\Models\User;
use App\Utils\Token;

use App\Middleware\Auth;

?>

<head>
<style>
  #navbar {
    overflow: hidden;
    background-color: #f1f1f1;
    padding: 30px 10px;
    transition: 0.4s;
    position: fixed;
    width: 100%;
    top: 0;
    bottom: auto;
    z-index: 99;
  }

  #navbar a {
    float: left;
    color: black;
    text-align: center;
    padding: 12px;
    text-decoration: none;
    font-size: 18px;
    line-height: 25px;
    border-radius: 4px;
  }

  #navbar #logo {
    font-size: 35px;
    font-weight: bold;
    transition: 0.4s;
  }

  #navbar a:hover {
    background-color: #ddd;
    color: black;
  }

  #navbar a.active {
    background-color: #f1f1f1;
    color: black;
  }

  #navbar-right {
    float: right;
  }

  @media screen and (max-width: 1100px) {
    #navbar {
      padding: 20px 10px !important;
    }
    #navbar a {
      float: none;
      display: block;
      text-align: left;
    }
    #navbar-right {
      float: none;
    }
  }
</style>

<nav id="navbar">
  <a href="https://nsbm.ac.lk" id="logo">
    <img src="https://www.nsbm.ac.lk/wp-content/uploads/2022/12/logo_nsbm.png" width="80px" />
  </a>
  <div id="navbar-right">
    <a class="active" href="/">Home</a>
    <a href="/question">Question page</a>
    <a href="past-questions">Past Question</a>
    <a href="/leaderboard">Leadboard</a>
    <a href="/about">Aboutus</a>
    <a href="/contact">Contact us</a>
    <?php if(Auth::isAdmin()) : ?>
        <a href="/admin">Admin</a>
    <?php endif; ?>
    <?php if(Auth::isAuthed()) : ?>
        <?php
            $session = $_COOKIE['session'];
            $verified = Token::decode($session);
        if (!$verified) {
            echo '<a href="/login">Login</a>';
            return;
        }
            $user = new User;
        try {
            $user->fetchUser(username: Token::$sub);
        } catch (Exception $e) {
            return;
        }
        ?>

        <a href="/logout">
          <img src=<?php echo $user->getPhotoURL() ?> style="border-radius: 50%;" width="45px"/>
        </a>
    <?php else: ?>
        <a href="/login">Login</a>
    <?php endif; ?>
  </div>
</nav>
</head>
