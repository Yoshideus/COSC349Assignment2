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

    $q = "SELECT * FROM stats";

    $data = $conn->query($q);

    // set count variable to 0 and scores
    $i = 0;

    // reading to the end of the file
    while($row = $data->fetch_assoc()){
      // setting row into array, collectivly putting file in 2d array
      $score[$i][0] = $row["username"];
      $score[$i][1] = $row["gamesplayed"];
      $score[$i][2] = $row["wins"];
      $score[$i][3] = $row["draws"];
      $score[$i][4] = $row["loses"];
      $score[$i][5] = $row["score"];
      $score[$i][6] = $row["winrate"];

      // count variable counting up for each row
      $i++;
    }
      // setting max to count
      $max = $i;
     ?>
  </head>
  <body>
    <!-- open container -->
    <div class="container">
      <!-- header for title -->
        <header>
          <!-- page title -->
        <h2>Leaderboard</h2>
        <!-- form for logout button -->
      <form method="post">
        <!-- logout button -->
        <input class="logout" type="submit" name="logout" value="Log out">
      </form>
    </header>
    <!-- break for spacing -->
    <br>
      <!-- div for section -->
      <div class="wrapper">
        <!-- leaderboard table -->
        <table class="leaderboard">
          <!-- leaderboard titles -->
          <thead>
            <!-- leaderboard column titles -->
            <th>Rank</th>
            <th>Username</th>
            <th>Score</th>
            <th>Winrate</th>
          </thead>
          <?php
          //linear sort by G.Witherow *modified*
          //sorting by score
          //start with assumed failure
          $sorted = false;
          while ($sorted == false) {
            //if no number is out of place, sorting is done
            $sorted = true;
            //run through the list
            for ($i=0; $i < $max-1; $i++) {
              //if the proceeding value is lower than the receeding, swap em
              if ($score[$i][5] < $score[$i + 1][5]) {
                $temp = $score[$i + 1];
                $score[$i + 1] = $score[$i];
                $score[$i] = $temp;
                //and tell the program more sorting needs to happen
                $sorted = false;
              }
            }
          }
          // if score same, sorting by winrate
          $sorted = false;
          while ($sorted == false) {
            //if no number is out of place, sorting is done
            $sorted = true;
            //run through the list
            for ($i=0; $i < $max-1; $i++) {
              //if the proceeding value is lower than the receeding, swap em
              if ($score[$i][5] == $score[$i + 1][5]) {
                if ($score[$i][6] < $score[$i + 1][6]) {
                  $temp = $score[$i + 1];
                  $score[$i + 1] = $score[$i];
                  $score[$i] = $temp;
                  //and tell the program more sorting needs to happen
                  $sorted = false;
                }
              }
            }
          }

          // removing added empty line
          array_pop($score);

          // setting secondary counting variable
          $j = 1;

              // putting sorted array in table by row
              foreach ($score as $row) {  ?>
                  <!-- table row -->
                  <tr>
                    <!-- table boxes -->
                    <!-- counting variable as rank number -->
                    <td><?php echo $j ?></td>
                    <!-- name for array -->
                    <td><?php echo $row[0]; ?></td>
                    <!-- score from array -->
                    <td><?php echo $row[5]; ?></td>
                    <!-- winrate with 2 dcimal places (%) -->
                    <td><?php echo number_format((float)($row[6] * 100), 2, '.', '')."%"; ?></td>

              <!-- counting up for rank -->
          <?php $j++; } ?>

                </tbody>
              </table>
      </div>
      <!-- for for back and foward buttons -->
      <form>
        <!-- back input -->
        <input class="back" type="submit" name="back" value="Back to Menu">
        <!-- break for speration -->
        <br>
        <!-- input for foward button -->
        <input class="foward" type="submit" name="foward" value="Play Game">
      </form>
    </div>
  </body>
</html>
