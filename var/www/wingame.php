<?php

// starting session for session variables
session_start();

//checking if user has logged in and if not then takes them to login/register page
if(!isset($_SESSION['username'])){
  header("Location: index.php"); /* Redirect browser */
  exit;
}
if(!isset($_SESSION['gametype'])){
  header("Location: gamemode.php"); /* Redirect browser */
  exit;
}
if(!isset($_SESSION['result'])){
  $_SESSION['result'] = "drew";
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
    // if gametype AI
    if($_SESSION['gametype'] == "AI"){
      // if user won
      if($_SESSION['result'] == "win"){
        // set win message
        $wintext = "You beat the ".$_SESSION['AI']." AI!";
      }
      // if user lost
      elseif($_SESSION['result'] == "lose"){
        // set win message
        $wintext = "You were beat by the ".$_SESSION['AI']." AI!";
      }
      // if draw
      else{
        // set win message
        $wintext = "You drew against the ".$_SESSION['AI']." AI!";
      }
    }
    // if gametype pvp
    elseif($_SESSION['gametype'] == "PVP"){
      // if a user won
      if($_SESSION['result'] == "win"){
        // set win message
        $wintext = $_SESSION['token']." won!";
      }
      // if draw
      else{
        // set win message
        $wintext = "You drew!";
      }
    }
    // if gametype online
    else {
      // if win
      if($_SESSION['result'] == "win"){
        // set win message
        $wintext = "You beat ".$_SESSION['opponent']."!";
      }
      // if draw
      else{
        // set win message
        $wintext = "You drew against ".$_SESSION['opponent']."!";
      }
    }

    // if back button is selected
    if(isset($_POST['back'])){
      // unset any variables used
      $_SESSION['token'] = null;
      $_SESSION['result'] = null;
      $_SESSION['AI'] = null;
      // if AI gametype, back to choose ai page
      if($_SESSION['gametype'] == "AI") {
        header("Location: chooseAI.php"); /* Redirect browser */
        exit;
      }
      // if pvp gametype back to gamemode page
      elseif($_SESSION['gametype'] == "PVP") {
        header("Location: gamemode.php"); /* Redirect browser */
        exit;
      }
      // if online gametype, back to select game page
      else {
        header("Location: selectgame.php"); /* Redirect browser */
        exit;
      }
    }
     ?>
   </head>
   <body>
     <!-- open container -->
     <div class="container">
       <!-- div  -->
       <div class=".wintext">
          <!-- win mssage depending on situation -->
          <h2 class="wintext"><?php echo $wintext; ?></h2>
          <!-- form for back button -->
          <form method="post">
            <!-- link to go back to the previous menu -->
            <input class="menu" type="submit" name="back" value="Back">
          </form>
        </div>
      </div>
  </body>
</html>
