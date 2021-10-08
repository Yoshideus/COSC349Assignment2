<?php

// starting session for session variables
session_start();

// reseting session variables
$_SESSION['gametype'] = null;
$_SESSION['game'] = null;
$_SESSION['turn'] = null;

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
    // if easy button is selected
    if(isset($_POST['easy'])){
      // set easy session variable to easy
      $_SESSION['AI'] = "easy";
      // bring user to game AI page
      header("Location: gameAI.php"); /* Redirect browser */
      exit;
    }
    // same as easy for medium
    if(isset($_POST['medium'])){
      $_SESSION['AI'] = "medium";
      header("Location: gameAI.php"); /* Redirect browser */
      exit;
    }
    // same as easy for hard
    if(isset($_POST['hard'])){
      $_SESSION['AI'] = "hard";
      header("Location: gameAI.php"); /* Redirect browser */
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
          <h2>AI Difficulty</h2>
            <!-- form for logout button -->
          <form method="post">
            <!-- logout button -->
            <input class="logout" type="submit" name="logout" value="Log out">
          </form>
        </header>
          <!-- form for AI difficulty button -->
          <form method="post">
          <!-- Input button for AI difficulty -->
          <input class="menu top" type="submit" name="easy" value="Easy">
          <!-- break to move menu below each other -->
          <br>
          <input class="menu" type="submit" name="medium" value="Medium">
          <br>
          <input class="menu" type="submit" name="hard" value="Hard">
          <br>
          <!-- link to go back to the previous menu -->
          <a href="gamemode.php"><button class="menu" type="button" name="button">Back</button></a>
          </form>
      </div>
  </body>
</html>
