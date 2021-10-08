<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// starting session for session variables
session_start();

// canceling opponent and gametype session variables
$_SESSION['opponent'] = null;
$_SESSION['gametype'] = null;

//checking if user has logged in and if not then takes them to login/register page
if(!isset($_SESSION['username'])){
  header("Location: index.php"); /* Redirect browser */
  exit;
}

$db_host   = '192.168.2.12';
$db_name   = 'fvision';
$db_user   = 'webuser';
$db_passwd = 'insecure_db_pw';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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

    $q = "SELECT * FROM games WHERE user1=? OR user2=?";

    $enter = $conn->prepare($q);

    $enter->bind_param("ss", $_SESSION['username'], $_SESSION['username']);

    $enter->execute();

    $result = $enter->get_result();

    // set counting variable
    $i = 0;

    // set games array
    $games = [];

    // set turn lists arrays
    $turn = [];
    $notturn = [];

    // algoithum to find all games the user in involved in, checking both player 1 and player 2, by running through the file and makes a 2 lists of the user's turn and the opponents turn.
    // reading to the end of the file
    while($row = $result->fetch_assoc()){

          if($row["whoseturn"] == $_SESSION['username']) {
            // put game into not turn list
            $turn[$i][0] = $row["user1"];
            $turn[$i][1] = $row["user2"];
            $turn[$i][2] = $row["turnnum"];
            $turn[$i][3] = $row["gameid"];
          }
          // if it is the user's turn
          else {
            // put game into turn list
            $notturn[$i][0] = $row["user1"];
            $notturn[$i][1] = $row["user2"];
            $notturn[$i][2] = $row["turnnum"];
            $notturn[$i][3] = $row["gameid"];
          }
      // counting up for each row
      $i++;
  }

      // if the user chooses a random opponent
      if(isset($_POST['random'])) {

        $q = "SELECT username FROM users";

        $data = $conn->query($q);

        // set counting variable
        $count = 0;

        // reading to the end of the file
        while($row = $data->fetch_assoc()){
          // set users as 2d array
          $users[] = $row["username"];
        }

        // setting variables
        $success = false;

        // looping until player is not the user
        while ($success == false){
          if(sizeof($users) < 2) {
            ?>
            <script>
            // alert for popup
            alert("No other user can be found");
            </script>
            <?php
            header("Location: selectgame.php");
          }
          // fidnign random player in users
          $opp = $users[array_rand($users)];
          // getting out of loop if the opponent isnt the user
          if($opp !== $_SESSION['username'] && $opp !== '') {
            $success = true;
          }
        }

        // setting the oppoent session variable
        $_SESSION['opponent'] = $opp;

        $q = 'INSERT INTO games (user1, user2, turnnum, whoseturn, p1, p2, p3, p4, p5, p6, p7, p8, p9) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $enter = $conn->prepare($q);

        $b = '';
        $n = 0;

        if ($enter->bind_param("ssdssssssssss", $_SESSION['username'], $opp, $n, $_SESSION['username'], $b, $b, $b, $b, $b, $b, $b, $b, $b) === TRUE) {
          echo "successfully";
        } else {
          echo "Error: " . $q . "<br>" . $conn->error . "<br>";
        }

        if ($enter->execute() === TRUE) {
          echo "New game created successfully";
        } else {
          echo "Error: " . $q . "<br>" . $conn->error . "<br>";
        }

        $id = $conn->insert_id;

        echo $id;

        // exit to the game with the row number to grab the game
        header("Location: game.php?id=$id"); /* Redirect browser */
        exit();
      }

      // when username find game button pressed
      if(isset($_POST['user'])){
        // getting username from input
        $opp = $_POST['username'];

        // setting error variable
        $error = "true";

        $q = "SELECT username FROM users";

        $data = $conn->query($q);

        // reading to the end of the file
        while($row = $data->fetch_assoc()){
          // set users as 2d array
          if($row['username'] == $opp){
            $error = "false";
          }
        }

        if($opp == $_SESSION['username']) {
          $error = 'true';
        }

        if($error == 'false') {

          $q = 'INSERT INTO games (user1, user2, turnnum, whoseturn, p1, p2, p3, p4, p5, p6, p7, p8, p9) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

          $enter = $conn->prepare($q);

          $b = '';
          $n = 0;

          if ($enter->bind_param("ssdssssssssss", $_SESSION['username'], $opp, $n, $_SESSION['username'], $b, $b, $b, $b, $b, $b, $b, $b, $b) === TRUE) {
            echo "successfully";
          } else {
            echo "Error: " . $q . "<br>" . $conn->error . "<br>";
          }

          if ($enter->execute() === TRUE) {
            echo "New game created successfully";
          } else {
            echo "Error: " . $q . "<br>" . $conn->error . "<br>";
          }

          $id = $conn->insert_id;

            // setting oppoent session avriable
            $_SESSION['opponent'] = $opp;

            // exit to the game with the row number to grab the game
            header("Location: game.php?id=$id"); /* Redirect browser */
            exit;
          }
          else {
            // popup to tell alert user that their entry was incorrect
            //opening js for alert
            ?>
            <script>
            // alert for popup
            alert("That User does not exist or you have entered yourself");
            </script>
            <?php
          }
        }
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
        <h2>Select Game</h2>
        <!-- form for logout button -->
      <form method="post">
        <!-- logout button -->
        <input class="logout" type="submit" name="logout" value="Log out">
      </form>
      </header>
      <!-- break for spacing -->
      <br>
          <!-- left section -->
          <div class="statleft">
            <!-- small title -->
            <h4>CONTINUE GAME</h4>
              <!-- smaller title -->
              <p>YOUR TURN</p>
              <!-- blue background div -->
              <div class="blue">
                <!-- table for list of games where it's the user's turn -->
                <table class="select">
                  <?php
                  // checking which player in active game is the opponent
                  foreach ($turn as $row) {
                    // if player one is user, player 2 is opponent
                    if($row[0] ==  $_SESSION['username']) {
                      $opp = $row[1];
                    }
                    // else player 1 is opponent
                    else {
                      $opp = $row[0];
                    }
                    ?>
                    <tr>
                        <!-- first box is opponent -->
                        <td class="active"><?php echo $opp; ?></td>
                        <!-- then turn number thats about to happen -->
                        <td class="active"><?php echo $row[2] + 1; ?></td>
                        <!-- play link to play -->
                        <td class="active"><a href="game.php?id=<?php echo $row[3] ?>">Play</a></td>
              <?php } ?>
                </table>
              </div>
              <!-- smaller title -->
              <p>OPPONENT'S TURN</p>
              <!-- blue background div -->
              <div class="blue">
                <!-- table for list of games where it's the opponent's turn -->
                  <table class="select">
                    <?php
                    // checking which player in active game is the opponent
                    foreach ($notturn as $row) {
                      // if player one is user, player 2 is opponent
                      if($row[0] ==  $_SESSION['username']) {
                        $opp = $row[1];
                      }
                      // else player 1 is opponent
                      else {
                        $opp = $row[0];
                      }
                      ?>
                        <tr>
                          <!-- first box is opponent -->
                          <td><?php echo $opp; ?></td>
                          <!-- then turn number thats about to happen -->
                          <td><?php echo $row[2] + 1; ?></td>
                <?php } ?>
                  </table>
                </div>
          </div>
          <!-- right div -->
          <div class="selectright">
            <!-- small title -->
            <h4>FIND A GAME</h4>
              <!-- smaller title -->
              <p>ENTER A USERNAME</p>
                <!-- form for posting username -->
                <form method="post">
                  <!-- blue background div -->
                  <div class="blue">
                    <!-- break for spacing -->
                    <br>
                    <!-- input for username, requried -->
                    <input class="findgame" type="text" name="username" value="" required>
                    <!-- break for spacing -->
                    <br>
                    <br>
                    <!-- button to submit username for game -->
                    <input class="findgame" type="submit" name="user" value="Find Game">
                    <!-- break for spacing -->
                    <br>
                    <br>
                  </div>
                </form>
                    <!-- smaller title -->
                    <p>RANDOM OPPONENT</p>
                    <!-- input for rnaodm game submition -->
                    <form method="post">
                    <!-- blue background div -->
                    <div class="blue">
                      <!-- break for spacing -->
                      <br>
                      <!-- button to submit for random game -->
                      <input class="findgame" type="submit" name="random" value="Find Game">
                      <!-- break for spacing -->
                      <br>
                      <br>
                    </div>
                </form>
              </div>
          </div>
          <!-- for for back button -->
          <form>
            <!-- back input -->
            <input class="selectback" type="submit" name="back" value="Back">
            <!-- break for speration -->
            <br>
          </form>
    </div>
  </body>
</html>
