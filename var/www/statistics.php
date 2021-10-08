<?php

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

    $nogames = $row["gamesplayed"];
    $wins = $row["wins"];
    $draws = $row["draws"];
    $loses = $row["loses"];
    $score = $row["score"];
    $winrate = $row["winrate"] * 100;
     ?>
  </head>
  <body>
    <!-- open container -->
    <div class="container">
      <!-- header for title -->
        <header>
          <!-- page title -->
        <h2>Statistics</h2>
        <!-- form for logout button -->
      <form method="post">
        <!-- logout button -->
        <input class="logout" type="submit" name="logout" value="Log out">
      </form>
    </header>
    <!-- break for spacing -->
      <br>
      <!-- div for stat titles -->
      <div class="statleft">
        <!-- small title for stat table -->
        <h5>Statistic</h5>
        <!-- names of stats -->
        <p>No. Games Played</p>
        <p>Wins</p>
        <p>Draws</p>
        <p>Loses</p>
        <p>Score</p>
        <p>Winrate</p>
      </div>
      <!-- div for stat figures -->
      <div class="statright">
        <!-- small title for table -->
        <h5>Figure</h5>
        <!-- stat figure from varibales -->
        <p><?php echo $nogames; ?></p>
        <p><?php echo $wins; ?></p>
        <p><?php echo $draws; ?></p>
        <p><?php echo $loses; ?></p>
        <p><?php echo $score; ?></p>
        <!-- winrate with 2 dcimal places (%) -->
        <p><?php echo number_format((float)$winrate, 2, '.', '')."%"; ?></p>
      </div>
      <!-- for for back and foward buttons -->
      <form>
        <!-- back input -->
        <input class="statback" type="submit" name="back" value="Back to Menu">
        <!-- break for speration -->
        <br>
        <!-- input for foward button -->
        <input class="statfoward" type="submit" name="foward" value="Play Game">
      </form>
    </div>
  </body>
</html>
