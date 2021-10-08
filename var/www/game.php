<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// starting session for session variables
session_start();

//checking if user has logged in and if not then takes them to login/register page
if(!isset($_SESSION['username'])){
  header("Location: index.php"); /* Redirect browser */
  exit;
}
// if the row variable that decides the game isn't set, go back to select game page
if(!isset($_GET['id'])){
  header("Location: selectgame.php"); /* Redirect browser */
  exit;
}

$db_host   = '192.168.2.12';
$db_name   = 'fvision';
$db_user   = 'webuser';
$db_passwd = 'insecure_db_pw';

$conn = new mysqli($db_host, $db_user , $db_passwd, $db_name);

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

    //getting current row from last page
    $id = $_GET['id'];

    $q = "SELECT * FROM games WHERE gameid=?";

    $enter = $conn->prepare($q);

    $enter->bind_param("s", $id);

    if ($enter->execute() === TRUE) {
      echo "success";
    } else {
      echo "Error: " . $q . "<br>" . $conn->error . "<br>";
    }

    $result = $enter->get_result();

    $row = $result->fetch_assoc();

    if($row['whoseturn'] != $_SESSION['username']){
      header("Location: selectgame.php"); /* Redirect browser */
      exit;
    }

    echo $row['p1']."<br></br>";

    $l = 5;
    $game = array (
      array($row['p1'],$row['p2'],$row['p3']),
      array($row['p4'],$row['p5'],$row['p6']),
      array($row['p7'],$row['p8'],$row['p9']),
    );

      // setting player 1, player 2, and turn number for game
      $player1 = $row["user1"];
      $player2 = $row["user2"];
      $turnno = $row["turnnum"];

      echo $player1;
      echo $player2;

      // setting token and opponent by user
      // if user is player 1
      if($_SESSION['username'] == $player1) {
        // their token is X and player 2 is their opponent
        $token = "X";
        $_SESSION['opponent'] = $player2;
      }
      // if user is player 2
      else {
        // their token is O and player 1 is their opponent
        $token = "O";
        $_SESSION['opponent'] = $player1;
      }

    // if confirm move button pressed
    if (isset($_POST['confirm'])) {
      // if move is selected
      if (isset($_POST['place'])) {
        // get place from input
        $place = $_POST['place'];

        // set row by function of place variable
        $line = intval($place/3);
        // set column but function of place variable
        $column = fmod($place, 3);

        $newturnnum = $turnno+1;

        // set the position selected to th token of the current player
        $game[$line][$column] = $token;

            // setting win and endgame variables
            $win = "false";
            $endgame = "false";

            // Algorithm for checking win. This algorithm uses for and if functions to automatically decide if it's 3 in a row. This checks rows, then columns then diaginals at the end. It is as efficent by using for loops to run through all the rows and columns and if statements reading as little lines as possible, only checking if the previous position is full set to the right token. It also only needs to check the current player as you can only win on your turn.
            // for loop to read through the rows
            for($i = 0; $i < 3; $i++) {
              // if statements to only progress if the last one is set
              if($game[$i][0] == $token){
                if($game[$i][1] == $token) {
                  if($game[$i][2] == $token) {
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
              if($game[0][$i] == $token){
                if($game[1][$i] == $token) {
                  if($game[2][$i] == $token) {
                    // if 3 in a row, setting win and endgame variables to true
                      $win = "true";
                      $endgame = "true";
                  }
                }
              }
            }
              // checking the first diagonal
              // if statements to only progress if the last one is set
              if($game[0][0] == $token){
                if($game[1][1] == $token) {
                  if($game[2][2] == $token) {
                    // if 3 in a row, setting win and endgame variables to true
                      $win = "true";
                      $endgame = "true";
                  }
                }
              }
              // checking the second diagonal
              // if statements to only progress if the last one is set
              if($game[2][0] == $token){
                if($game[1][1] == $token) {
                  if($game[0][2] == $token) {
                    // if 3 in a row, setting win and endgame variables to true
                      $win = "true";
                      $endgame = "true";
                  }
                }
              }
              // if the board has been filled with no wins, end game with no win
              if($newturnnum == 9){
                $endgame = "true";
              }

            // if game over
            if($endgame == "true") {
              $q = "SELECT * FROM stats WHERE username=?";

              $enter = $conn->prepare($q);

              $enter->bind_param("s", $_SESSION['username']);

              if ($enter->execute() === TRUE) {
                echo "success";
              } else {
                echo "Error: " . $q . "<br>" . $conn->error . "<br>";
              }

              $result = $enter->get_result();

              $row = $result->fetch_assoc();

              $newGamesPlayed = $row['gamesplayed']+1;
              if($win == "true") {
                // plus 1 to wins
                $newWins = $row['wins']+1;
                $newDraws = $row['draws'];
              }
              // if draw
              else {
                // plus 1 to draw
                $newWins = $row['wins'];
                $newDraws = $row['draws']+1;
              }
                // work out score for 3 points for a win and 1`point for a draw
                $newScore = 3*$newWins + $newDraws;
                // work out winrate by number of wins divided by number of games
                $newWinrate = $newWins/$newGamesPlayed;

              $q = 'UPDATE stats SET gamesplayed=?, wins=?, draws=?, score=?, winrate=? WHERE username = ?';

              $enter = $conn->prepare($q);

              $enter->bind_param("iiiids", $newGamesPlayed, $newWins, $newDraws, $newScore, $newWinrate, $_SESSION['username']);

              if ($enter->execute() === TRUE) {
                echo "New record created successfully 2";
              } else {
                echo "Error: " . $q . "<br>" . $conn->error . "<br>";
              }

              $q = "SELECT * FROM stats WHERE username=?";

              $enter = $conn->prepare($q);

              $enter->bind_param("s", $_SESSION['opponent']);

              if ($enter->execute() === TRUE) {
                echo "success";
              } else {
                echo "Error: " . $q . "<br>" . $conn->error . "<br>";
              }

              $result = $enter->get_result();

              $row = $result->fetch_assoc();

              $newGamesPlayed = $row['gamesplayed']+1;
              if($win == "true") {
                // plus 1 to wins
                $newLoses = $row['loses']+1;
                $newDraws = $row['draws'];
              }
              // if draw
              else {
                // plus 1 to draw
                $newWins = $row['wins'];
                $newDraws = $row['draws']+1;
              }
                // work out score for 3 points for a win and 1`point for a draw
                $newScore = 3*$newWins + $newDraws;
                // work out winrate by number of wins divided by number of games
                $newWinrate = $newWins/$newGamesPlayed;

              $q = 'UPDATE stats SET gamesplayed=?, loses=?, draws=?, score=?, winrate=? WHERE username = ?';

              $enter = $conn->prepare($q);

              $enter->bind_param("iiiids", $newGamesPlayed, $newLoses, $newDraws, $newScore, $newWinrate,  $_SESSION['username']);

              if ($enter->execute() === TRUE) {
                echo "New record created successfully 2";
              } else {
                echo "Error: " . $q . "<br>" . $conn->error . "<br>";
              }

              $q = 'DELETE FROM games WHERE gameid=?';

              $enter = $conn->prepare($q);

              $enter->bind_param("s", $id);

              if ($enter->execute() === TRUE) {
                echo "New record deleted successfully 2";
              } else {
                echo "Error: " . $q . "<br>" . $conn->error . "<br>";
              }

                // if user won
                if($win == "true"){
                  // set result for winscreen
                  $_SESSION['result'] = "win";
                }
                // set game type for win screen
                $_SESSION['gametype'] = "online";
                // being user to win screen
                header("Location: wingame.php"); /* Redirect browser */
                exit;
              } else {
                echo $place;

                switch($place) {
                  case 0:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p1=? WHERE gameid = ?';
                    break;
                  case 1:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p2=? WHERE gameid = ?';
                    break;
                  case 2:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p3=? WHERE gameid = ?';
                    break;
                  case 3:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p4=? WHERE gameid = ?';
                    break;
                  case 4:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p5=? WHERE gameid = ?';
                    break;
                  case 5:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p6=? WHERE gameid = ?';
                    break;
                  case 6:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p7=? WHERE gameid = ?';
                    break;
                  case 7:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p8=? WHERE gameid = ?';
                    break;
                  case 8:
                    $q = 'UPDATE games SET turnnum=?, whoseturn=?, p9=? WHERE gameid=?';
                    break;
                }

                $enter = $conn->prepare($q);

                $enter->bind_param("issi", $newturnnum, $_SESSION['opponent'], $token, $id);

                if ($enter->execute() === TRUE) {
                  echo "UPDATE";
                } else {
                  echo "Error: " . $q . "<br>" . $conn->error . "<br>";
                }
              }

            // empty opponentsession variable
            $_SESSION['opponent'] = null;
            // bring user to select game screen
            header("Location: selectgame.php"); /* Redirect browser */
            exit;
          }
        // if move not set
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
              <!-- game details left div -->
              <div class="topleft">
                <!-- display player 1 and 2 -->
                <p>Player 1: <?php echo $player1; ?></p>
                <p>Player 2: <?php echo $player2; ?></p>
              </div>
              <!-- game details right div -->
              <div class="topright">
                <!-- display what your token is and the turn number -->
                <p>Your Token: <?php echo $token; ?></p>
                <p>Current Turn: <?php echo $turnno + 1; ?></p>
              </div>
              <!-- game board -->
              <table class="board">
                <!-- form for game inputs -->
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
                      <td class="gameplace"><?php if($game[$i][$j] == '') {
                                                    // if it's not set, set a radio button for selection with the token for the selection display. $k for each square to have a unique value for collecting it
                                                    echo "<input type='radio' class='option-input".$token." radio ' name='place' value='$k'>"; }
                                                  else {
                                                    // if it is set, output the token it's set to
                                                    echo $game[$i][$j];
                                                  } ?>
                                                    </td>
                                                    <!-- variable counts up -->
                                                  <?php $k++; } ?>
                                                <!-- once done with row, moves on to next row -->
                                                </tr>
                                                <?php } ?>
              </table>
            </div>
            <!-- confirm turn button -->
            <input class="confirm" type="submit" name="confirm" value="Confirm Move">
          </form>
    </div>
  </body>
</html>
