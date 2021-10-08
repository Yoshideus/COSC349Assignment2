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

    // setting play token to X
    $token = 'X';

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

    // if confirm move button pressed
    if (isset($_POST['confirm'])) {
      // if move is selected
      if (isset($_POST['place'])) {
        // get place from input
        $place = $_POST['place'];

        // set row by function of place variable
        $row = intval($place/3);
        // set column but function of place variable
        $column = fmod($place, 3);

        // set the position selected to th token of the current player
        $_SESSION['game'][$row][$column] = 'X';

        // setting win, endgame and success variables
        $win = "false";
        $endgame = "false";
        $success = false;

        // Algorithm for checking win. This algorithm uses for and if functions to automatically decide if it's 3 in a row. This checks rows, then columns then diaginals at the end. It is as efficent by using for loops to run through all the rows and columns and if statements reading as little lines as possible, only checking if the previous position is full set to the right token. It also only needs to check the current player as you can only win on your turn.
        // for loop to read through the rows
        for($i = 0; $i < 3; $i++) {
          // if statements to only progress if the last one is set
          if($_SESSION['game'][$i][0] == $token){
            if($_SESSION['game'][$i][1] == $token) {
              if($_SESSION['game'][$i][2] == $token) {
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
          if($_SESSION['game'][0][$i] == $token) {
            if($_SESSION['game'][1][$i] == $token) {
              if($_SESSION['game'][2][$i] == $token) {
                // if 3 in a row, setting win and endgame variables to true
                  $win = "true";
                  $endgame = "true";
              }
            }
          }
        }
          // checking the first diagonal
          // if statements to only progress if the last one is set
          if($_SESSION['game'][0][0] == $token){
            if($_SESSION['game'][1][1] == $token) {
              if($_SESSION['game'][2][2] == $token) {
                // if 3 in a row, setting win and endgame variables to true
                  $win = "true";
                  $endgame = "true";
              }
            }
          }
          // checking the second diagonal
          // if statements to only progress if the last one is set
          if($_SESSION['game'][2][0] == $token){
            if($_SESSION['game'][1][1] == $token) {
              if($_SESSION['game'][0][2] == $token) {
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

          // if the game is over
            if($endgame == "true") {
              // if the player won
              if($win == "true"){
                // set result to win
                $_SESSION['result'] = "win";
              }
                // reset game functions
                $_SESSION['game'] = null;
                $_SESSION['turn'] = null;
                // set game type to AI
                $_SESSION['gametype'] = "AI";
                // redirect user
                header("Location: wingame.php"); /* Redirect browser */
                exit;
            }
            // if the game isnt over
            else {
              // Algorithm to let the AI take it's turn
              // if AI is easy (this AI will pick a random move that hasnt been taken yet)
              if($_SESSION['AI'] == "easy") {
                // select randdom row and column
                $moveR = rand(0,2);
                $moveC = rand(0,2);
                // checking if position is taken and choosing another position if it is
                while ($success == false) {
                  // select randdom row and column
                  $moveR = rand(0,2);
                  $moveC = rand(0,2);
                  // if move is free
                  if($_SESSION['game'][$moveR][$moveC] == '') {
                    // end move selection
                    $success = true;
                  }
                }
              }
              // if AI is medium (AI will choose random move unless you are about to win, if so, it will stop you)
              elseif($_SESSION['AI'] == "medium") {
                // select randdom row and column
                $moveR = rand(0,2);
                $moveC = rand(0,2);
                // checking if position is taken and choosing another position if it is
                while ($success == false) {
                  // select randdom row and column
                  $moveR = rand(0,2);
                  $moveC = rand(0,2);
                  // if move is free
                  if($_SESSION['game'][$moveR][$moveC] == '') {
                    // end random move selection
                    $success = true;
                  }
                }
                // checking all possible ways for user to be able to win
                // checking rows with column 3 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][0] == 'X' && $_SESSION['game'][$i][1] == 'X' && $_SESSION['game'][$i][2] == ''){
                    // if about to win, change move to the move that will stop the user from winning
                    $moveR = $i;
                    $moveC = 2;
                  }
                }
                // checking rows with column 2 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][0] == 'X' && $_SESSION['game'][$i][2] == 'X' && $_SESSION['game'][$i][1] == ''){
                    $moveR = $i;
                    $moveC = 1;
                  }
                }
                // checking rows with column 1 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][1] == 'X' && $_SESSION['game'][$i][2] == 'X' && $_SESSION['game'][$i][0] == ''){
                    $moveR = $i;
                    $moveC = 0;
                  }
                }
                // checking columnss with row 3 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][0][$i] == 'X' && $_SESSION['game'][1][$i] == 'X' && $_SESSION['game'][2][$i] == ''){
                    $moveR = 2;
                    $moveC = $i;
                  }
                }
                // checking columnss with row 2 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][0][$i] == 'X' && $_SESSION['game'][2][$i] == 'X' && $_SESSION['game'][1][$i] == ''){
                    $moveR = 1;
                    $moveC = $i;
                  }
                }
                // checking columnss with row 1 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][1][$i] == 'X' && $_SESSION['game'][2][$i] == 'X' && $_SESSION['game'][0][$i] == ''){
                    $moveR = 0;
                    $moveC = $i;
                  }
                }
                // checking all possible diagonal ways of victory with all possible moves missing to win
                if($_SESSION['game'][0][0] == 'X'){
                  if($_SESSION['game'][1][1] == 'X') {
                    if($_SESSION['game'][2][2] == '') {
                      $moveR = 2;
                      $moveC = 2;
                    }
                  }
                }
                if($_SESSION['game'][0][0] == 'X'){
                  if($_SESSION['game'][2][2] == 'X') {
                    if($_SESSION['game'][1][1] == '') {
                      $moveR = 1;
                      $moveC = 1;
                    }
                  }
                }
                if($_SESSION['game'][2][2] == 'X'){
                  if($_SESSION['game'][1][1] == 'X') {
                    if($_SESSION['game'][0][0] == '') {
                      $moveR = 0;
                      $moveC = 0;
                    }
                  }
                }
                if($_SESSION['game'][0][2] == 'X'){
                  if($_SESSION['game'][1][1] == 'X') {
                    if($_SESSION['game'][2][0] == '') {
                      $moveR = 2;
                      $moveC = 0;
                    }
                  }
                }
                if($_SESSION['game'][0][2] == 'X'){
                  if($_SESSION['game'][2][0] == 'X') {
                    if($_SESSION['game'][1][1] == '') {
                      $moveR = 1;
                      $moveC = 1;
                    }
                  }
                }
                if($_SESSION['game'][2][0] == 'X'){
                  if($_SESSION['game'][1][1] == 'X') {
                    if($_SESSION['game'][0][2] == '') {
                      $moveR = 0;
                      $moveC = 2;
                    }
                  }
                }
              }
              // if AI hard (AI will randomly select move, however if user about to win, stop then, however if AI can win this turn, win)
              elseif($_SESSION['AI'] == "hard") {
                // select randdom row and column
                $moveR = rand(0,2);
                $moveC = rand(0,2);
                // checking if position is taken and choosing another position if it is
                while ($success == false) {
                  // select randdom row and column
                  $moveR = rand(0,2);
                  $moveC = rand(0,2);
                  // if move is free
                  if($_SESSION['game'][$moveR][$moveC] == '') {
                    // end random move selection
                    $success = true;
                  }
                }
                // checking all possible ways for user to be able to win
                // checking rows with column 3 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][0] == 'X' && $_SESSION['game'][$i][1] == 'X' && $_SESSION['game'][$i][2] == ''){
                    // if about to win, change move to the move that will stop the user from winning
                    $moveR = $i;
                    $moveC = 2;
                  }
                }
                // checking rows with column 2 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][0] == 'X' && $_SESSION['game'][$i][2] == 'X' && $_SESSION['game'][$i][1] == ''){
                    $moveR = $i;
                    $moveC = 1;
                  }
                }
                // checking rows with column 1 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][1] == 'X' && $_SESSION['game'][$i][2] == 'X' && $_SESSION['game'][$i][0] == ''){
                    $moveR = $i;
                    $moveC = 0;
                  }
                }
                // checking columnss with row 3 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][0][$i] == 'X' && $_SESSION['game'][1][$i] == 'X' && $_SESSION['game'][2][$i] == ''){
                    $moveR = 2;
                    $moveC = $i;
                  }
                }
                // checking columnss with row 2 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][0][$i] == 'X' && $_SESSION['game'][2][$i] == 'X' && $_SESSION['game'][1][$i] == ''){
                    $moveR = 1;
                    $moveC = $i;
                  }
                }
                // checking columnss with row 1 being missing
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][1][$i] == 'X' && $_SESSION['game'][2][$i] == 'X' && $_SESSION['game'][0][$i] == ''){
                    $moveR = 0;
                    $moveC = $i;
                  }
                }
                // checking all possible diagonal ways of victory with all possible moves missing to win
                if($_SESSION['game'][0][0] == 'X'){
                  if($_SESSION['game'][1][1] == 'X') {
                    if($_SESSION['game'][2][2] == '') {
                      $moveR = 2;
                      $moveC = 2;
                    }
                  }
                }
                if($_SESSION['game'][0][0] == 'X'){
                  if($_SESSION['game'][2][2] == 'X') {
                    if($_SESSION['game'][1][1] == '') {
                      $moveR = 1;
                      $moveC = 1;
                    }
                  }
                }
                if($_SESSION['game'][2][2] == 'X'){
                  if($_SESSION['game'][1][1] == 'X') {
                    if($_SESSION['game'][0][0] == '') {
                      $moveR = 0;
                      $moveC = 0;
                    }
                  }
                }
                if($_SESSION['game'][0][2] == 'X'){
                  if($_SESSION['game'][1][1] == 'X') {
                    if($_SESSION['game'][2][0] == '') {
                      $moveR = 2;
                      $moveC = 0;
                    }
                  }
                }
                if($_SESSION['game'][0][2] == 'X'){
                  if($_SESSION['game'][2][0] == 'X') {
                    if($_SESSION['game'][1][1] == '') {
                      $moveR = 1;
                      $moveC = 1;
                    }
                  }
                }
                if($_SESSION['game'][2][0] == 'X'){
                  if($_SESSION['game'][1][1] == 'X') {
                    if($_SESSION['game'][0][2] == '') {
                      $moveR = 0;
                      $moveC = 2;
                    }
                  }
                }
                // exact same check for if user is able to win, but toke chnaged to O and therefore checks if the AI will win, and if it can, chnage move to winning move.
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][0] == 'O' && $_SESSION['game'][$i][1] == 'O' && $_SESSION['game'][$i][2] == ''){
                    $moveR = $i;
                    $moveC = 2;
                  }
                }
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][0] == 'O' && $_SESSION['game'][$i][2] == 'O' && $_SESSION['game'][$i][1] == ''){
                    $moveR = $i;
                    $moveC = 1;
                  }
                }
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][$i][1] == 'O' && $_SESSION['game'][$i][2] == 'O' && $_SESSION['game'][$i][0] == ''){
                    $moveR = $i;
                    $moveC = 0;
                  }
                }
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][0][$i] == 'O' && $_SESSION['game'][1][$i] == 'O' && $_SESSION['game'][2][$i] == ''){
                    $moveR = 2;
                    $moveC = $i;
                  }
                }
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][0][$i] == 'O' && $_SESSION['game'][2][$i] == 'O' && $_SESSION['game'][1][$i] == ''){
                    $moveR = 1;
                    $moveC = $i;
                  }
                }
                for ($i=0; $i < 3; $i++) {
                  if($_SESSION['game'][1][$i] == 'O' && $_SESSION['game'][2][$i] == 'O' && $_SESSION['game'][0][$i] == ''){
                    $moveR = 0;
                    $moveC = $i;
                  }
                }
                if($_SESSION['game'][0][0] == 'O'){
                  if($_SESSION['game'][1][1] == 'O') {
                    if($_SESSION['game'][2][2] == '') {
                      $moveR = 2;
                      $moveC = 2;
                    }
                  }
                }
                if($_SESSION['game'][0][0] == 'O'){
                  if($_SESSION['game'][2][2] == 'O') {
                    if($_SESSION['game'][1][1] == '') {
                      $moveR = 1;
                      $moveC = 1;
                    }
                  }
                }
                if($_SESSION['game'][2][2] == 'O'){
                  if($_SESSION['game'][1][1] == 'O') {
                    if($_SESSION['game'][0][0] == '') {
                      $moveR = 0;
                      $moveC = 0;
                    }
                  }
                }
                if($_SESSION['game'][0][2] == 'O'){
                  if($_SESSION['game'][1][1] == 'O') {
                    if($_SESSION['game'][2][0] == '') {
                      $moveR = 2;
                      $moveC = 0;
                    }
                  }
                }
                if($_SESSION['game'][0][2] == 'O'){
                  if($_SESSION['game'][2][0] == 'O') {
                    if($_SESSION['game'][1][1] == '') {
                      $moveR = 1;
                      $moveC = 1;
                    }
                  }
                }
                if($_SESSION['game'][2][0] == 'O'){
                  if($_SESSION['game'][1][1] == 'O') {
                    if($_SESSION['game'][0][2] == '') {
                      $moveR = 0;
                      $moveC = 2;
                    }
                  }
                }
              }

              // after selecting moves, set move for AI
              $_SESSION['game'][$moveR][$moveC] = "O";

              // same check for if user wins but for AI's token
              for($i = 0; $i < 3; $i++) {
                if($_SESSION['game'][0][$i] == 'O'){
                  if($_SESSION['game'][1][$i] == 'O') {
                    if($_SESSION['game'][2][$i] == 'O') {
                        $win = "true";
                        $endgame = "true";
                    }
                  }
                }
              }

              for($i = 0; $i < 3; $i++) {
                if($_SESSION['game'][$i][0] == 'O'){
                  if($_SESSION['game'][$i][1] == 'O') {
                    if($_SESSION['game'][$i][2] == 'O') {
                        $win = "true";
                        $endgame = "true";
                    }
                  }
                }
              }
                if($_SESSION['game'][0][0] == 'O'){
                  if($_SESSION['game'][1][1] == 'O') {
                    if($_SESSION['game'][2][2] == 'O') {
                        $win = "true";
                        $endgame = "true";
                    }
                  }
                }
                if($_SESSION['game'][2][0] == 'O'){
                  if($_SESSION['game'][1][1] == 'O') {
                    if($_SESSION['game'][0][2] == 'O') {
                        $win = "true";
                        $endgame = "true";
                    }
                  }
                }
                if($_SESSION['turn'] == 9){
                  $endgame = "true";
                }

                // if game over
                if($endgame == "true") {
                  // if AI won
                  if($win == "true") {
                    // set user result to lose
                    $_SESSION['result'] = "lose";
                  }
                  else{
                    $_SESSION['result'] = "drew";
                  }
                  // reset game variables
                  $_SESSION['game'] = null;
                  $_SESSION['turn'] = null;
                  // set gametype to AI
                  $_SESSION['gametype'] = "AI";
                  //redirect user to win page
                  header("Location: wingame.php"); /* Redirect browser */
                  exit;
                }

              // count turn up
              $_SESSION['turn']++;
        }
      }
      else {
        ?>
        <script>
        alert("Enter Move!");
        </script>
        <?php
      }
    }
    // if back button is selected
    if(isset($_POST['back'])){
      // unsetting variables
      $_SESSION['game'] = null;
      $_SESSION['turn'] = null;
      // bring user to choose AI
      header("Location: chooseAI.php"); /* Redirect browser */
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
                <!-- displaying Ai difficult thats playing -->
                <p>AI Difficulty: <?php echo $_SESSION['AI']; ?></p>
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
                                                    echo "<input type='radio' class='option-input".$token." radio ' name='place' value='$k'>"; }
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
