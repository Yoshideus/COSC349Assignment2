<?php

// starting session for session variables
session_start();

//checking if user has logged in and if not then takes them to login/register page
if(!isset($_SESSION['username'])){
  header("Location: index.php"); /* Redirect browser */
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <!-- use for different screen size -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- description -->
    <meta name="description" content="2018 MMOS Software Development SAT">

    <!-- author -->
    <meta name="author" content="Matthew Doyle">

    <!-- date last updated -->
    <meta name="date" content="28/8/2018">

    <!-- page icon -->
    <link rel="icon" href="images/icon.jpeg">

    <!-- page title -->
    <title>Soft Dev SAT</title>

    <!-- Main CSS -->
    <link rel="stylesheet" href='css/main.css'>

    <!-- opening php -->
    <?php

    // if logout button is pressed
    if(isset($_POST['logout'])){
      //cancel username
      $_SESSION['username'] = null;
      // take user to login/register page
      header("Location: index.php"); /* Redirect browser */
      exit;
    }
     ?>
  </head>
  <body>
    <!-- open container -->
    <div class="container">
      <!-- header for title -->
        <header>
          <!-- page title -->
          <h1 class="indextitle">Simple Games</h1>
          <!-- form for logout button -->
          <form method="post">
            <!-- logout button -->
            <input class="logout" type="submit" name="logout" value="Log out">
          </form>
        </header>
          <!-- menu links to bring you to their respective pages -->
          <a href="gamemode.php"><button class="menu top" type="button" name="button">Play Game</button></a>
          <!-- break to move menu below each other -->
          <br>
          <a href="instructions.php"><button class="menu" type="button" name="button">Instructions</button></a>
          <br>
          <a href="statistics.php"><button class="menu" type="button" name="button">Statistics</button></a>
          <br>
          <a href="leaderboard.php"><button class="menu" type="button" name="button">Leaderboard</button></a>
      </div>
  </body>
</html>
