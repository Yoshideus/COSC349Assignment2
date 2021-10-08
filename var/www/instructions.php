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
    // if back button is selected
    if(isset($_GET['back'])){
      // bring user to menu
      header("Location: menu.php"); /* Redirect browser */
      exit;
    }
    // if play game button is selected
    if(isset($_GET['foward'])){
      // bring user to game mode page
      header("Location: gamemode.php"); /* Redirect browser */
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
        <h2>Instructions</h2>
        <!-- form for logout button -->
      <form method="post">
        <!-- logout button -->
        <input class="logout" type="submit" name="logout" value="Log out">
      </form>
    </header>
      <!-- break for spacing -->
      <br>
        <!-- div for section -->
        <div class="rules">
          <!-- title for text -->
          <h3>Tic Tac Toe</h3>
            <!-- div for text -->
            <div class="text">
              <!-- text for rules, number and title highlighted -->
              <p><span class="highlight">1. Goal:</span> get three of your tokens in a row.</p>
              <p><span class="highlight">2. Basics:</span> you play on a three by three game board, each player playing 1 token per turn.</p>
              <p><span class="highlight">3. Playing a Move:</span> click an empty place on board, then click confirm move button.
              <p><span class="highlight">4. Tokens/Order:</span> The first player's token is an X and the second player's is an O. Online your token appears at the top right.</p>
              <p><span class="highlight">5. Game Ends:</span> when 3 of the same token are in a row or all squares are filled (draw).</p>
              <p><span class="highlight">6. Game Modes:</span> You can play online VS another player, local VS someone next to you, or VS an AI.</p>
              <p><span class="highlight">7. Scoring:</span> Wins = 3 points, draws = 1 point, and losses = 0 points. Only online games count towards your statistics.</p>
              <p><span class="highlight">8. Leaderboard:</span> There is a leaderboard of player, which is decided by score and then, if the score is the same by win rate.</p>
            </div>
          <!-- for for back and foward buttons -->
          <form>
            <!-- back input -->
            <input class="instback" type="submit" name="back" value="Back to Menu">
            <!-- break for speration -->
            <br>
            <!-- input for foward button -->
            <input class="instfoward" type="submit" name="foward" value="Play Game">
          </form>
    </div>
  </div>
  </body>
</html>
