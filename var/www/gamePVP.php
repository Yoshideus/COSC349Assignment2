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

        // when page first loaded, creating game board array
        if(!isset($_SESSION['game'])){
          // for loop to set all of the 2d array variables
          for ($j=0; $j < 3; $j++) {
            for ($k=0; $k < 3; $k++) {
              $_SESSION['game'][$j][$k] = '';
            }
          }
        }

        // when game reset, set turn to 1
        if(!isset($_SESSION['turn'])){
          $_SESSION['turn'] = 1;
        }

        // when game reset, set token to X
        if(!isset($_SESSION['token'])){
          $_SESSION['token'] = "X";
        }

        // setting win and endgame variables
        $win = "false";
        $endgame = "false";

        // setting the token to the right variable for each turn, X for odd turns and O for even
        if($_SESSION['turn'] == 1 || $_SESSION['turn'] == 3 || $_SESSION['turn'] == 5 || $_SESSION['turn'] == 7 || $_SESSION['turn'] == 9) {
          $_SESSION['token'] = 'X';
        }
        if($_SESSION['turn'] == 2 || $_SESSION['turn'] == 4 || $_SESSION['turn'] == 6 || $_SESSION['turn'] == 8) {
          $_SESSION['token'] = 'O';
        }

    // when confirm turn button pressed
    if (isset($_POST['confirm'])) {
      // if move selected
      if (isset($_POST['place'])) {

        // grabs move
        $place = $_POST['place'];
        // turns string position into 2d array position using equation to work out column and row
        $row = intval($place/3);
        $column = fmod($place, 3);

        // setting move in array board
        $_SESSION['game'][$row][$column] = $_SESSION['token'];

      // Algorithm for checking win. This algorithm uses for and if functions to automatically decide if it's 3 in a row. This checks rows, then columns then diaginals at the end. It is as efficent by using for loops to run through all the rows and columns and if statements reading as little lines as possible, only checking if the previous position is full set to the right token. It also only needs to check the current player as you can only win on your turn.
      // for loop to read through the rows
      for($i = 0; $i < 3; $i++) {
        // if statements to only progress if the last one is set
        if($_SESSION['game'][$i][0] == $_SESSION['token']){
          if($_SESSION['game'][$i][1] == $_SESSION['token']) {
            if($_SESSION['game'][$i][2] == $_SESSION['token']) {
                // if 3 in a row, setting win and endgame variables to true
                $win = "true";
                $endgame = "true";
            }
          }
        }
      }
      // for loop to read through the columns
      for($i = 0; $i < 3; $i++) {
        // if statements to only progress if the last one is set
        if($_SESSION['game'][0][$i] == $_SESSION['token']){
          if($_SESSION['game'][1][$i] == $_SESSION['token']) {
            if($_SESSION['game'][2][$i] == $_SESSION['token']) {
              // if 3 in a row, setting win and endgame variables to true
                $win = "true";
                $endgame = "true";
            }
          }
        }
      }
        // checking the first diagonal
        // if statements to only progress if the last one is set
        if($_SESSION['game'][0][0] == $_SESSION['token']){
          if($_SESSION['game'][1][1] == $_SESSION['token']) {
            if($_SESSION['game'][2][2] == $_SESSION['token']) {
              // if 3 in a row, setting win and endgame variables to true
                $win = "true";
                $endgame = "true";
            }
          }
        }
        // checking the second diagonal
        // if statements to only progress if the last one is set
        if($_SESSION['game'][2][0] == $_SESSION['token']){
          if($_SESSION['game'][1][1] == $_SESSION['token']) {
            if($_SESSION['game'][0][2] == $_SESSION['token']) {
              // if 3 in a row, setting win and endgame variables to true
                $win = "true";
                $endgame = "true";
            }
          }
        }
        // if the board has been filled with no wins, end game with no win
        if($_SESSION['turn'] == 9){
          $endgame = "true";
        }

        // after turn, increase turn number
        $_SESSION['turn']++;

            // if the game has ended
            if($endgame == "true") {
              if($win == "true") {
                $_SESSION['result'] = "win";
              }
              // once game's ended, reset board and turn number
              $_SESSION['game'] = null;
              $_SESSION['turn'] = null;
              // set game type
              $_SESSION['gametype'] = "PVP";
              // bring user to win page
              header("Location: wingame.php"); /* Redirect browser */
              exit;
            }

            //
            header("Location: gamePVP.php"); /* Redirect browser */
            exit;

          }
      else {
        // popup to tell alert user to select a move
        //opening js for alert
        ?>
        <script>
        // alert for popup
        alert("Enter Move");
        </script>
        <?php
      }
    }

    // if back button is selected
    if(isset($_POST['back'])){
      // unsetting variables
      $_SESSION['game'] = null;
      $_SESSION['turn'] = null;
      // bring user to gamemode
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
        <h2 class="gametitle">Tic Tac Toe</h2>
      </header>
            <!-- game div -->
            <div class="game">
              <!-- spacing -->
              <br>
              <br>
              <!-- game board -->
              <table class="board">
                <!-- form for game move inputs and buttons -->
                <form method="post">
                <?php
                  // setting third counting variable to 0
                  $k = 0;
                  //lalorithm for automatically setting up the board table with the array and different values for input.
                  // for loop for columns
                  for ($i=0; $i < 3; $i++) { ?>
                  <!-- open row -->
                  <tr>
                          <!-- for loop for each place in the rows -->
                    <?php for ($j=0; $j < 3; $j++) { ?>
                    <!-- table box -->          <!-- checking if the position is set -->
                    <td class="gameplace"><?php if($_SESSION['game'][$i][$j] == '') {
                                                  // if it's not set, set a radio button for selection with the token for the selection display. $k for each square to have a unique value for collecting it
                                                  echo "<input type='radio' class='option-input".$_SESSION['token']." radio ' name='place' value='$k'>"; }
                                                else {
                                                  // if it is set, output the token it's set to
                                                  echo $_SESSION['game'][$i][$j];
                                                } ?>
                                                  </td>
                                                  <!-- variable counts up -->
                                                <?php $k++; } ?>
                                              <!-- once done with row, moves on to next row -->
                                              </tr>
                                              <?php } ?>
              </table>
            </div>
            <!-- back input -->
            <input class="selectback" type="submit" name="back" value="Back">
            <!-- break for speration -->
            <br>
            <!-- input for confirm move button -->
            <input class="confirm" type="submit" name="confirm" value="Confirm Move">
          </form>
    </div>
  </body>
</html>
