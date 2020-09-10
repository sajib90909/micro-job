<?php
include '../database/config.php';
include "../database/database.php";
include "../func/session.php";
Session::checkSession(); //check login and start session
function test_input($data) {
    $db = new database();
    $data = trim($data);
    $data = strtolower($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($db->link,$data);
    return $data;
}
$db = new database();
?>
<?php if (isset($_GET['logout']) && $_GET['logout'] == "target") { // logout and destroy all session
    Session::destroy();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Micro Works</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css"> <!-- bootstrap css library call -->
    <script src="../assets/js/jquery.min.js"></script> <!-- jquery library call -->
    <script src="../assets/js/bootstrap.min.js"></script> <!-- bootstrap js library call -->
    <!-- <link rel="stylesheet" href="assets/css/all.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
  </head>
  <body>
    <div class="warrper">
      <div class="secction-one content-width">
        <div class="logo">
          <h4><a href="../index.php">Nirviqit</a></h4>
        </div>
        <div class="btn-group float-right">
          <?php
           if($_SESSION['login']){?>
            <a href="?logout=target" class="action-btn btn-light" name="button">Logout</a>
        <?php  }else{?>
          <a href="registration.php" class="action-btn btn-light" name="button">Sign Up</a>
          <a href="login.php" class="action-btn btn-light" name="button">Login</a>
      <?php  }?>

        </div>
      </div>
      <div class="section-two">
        <div class="content-area content-width">
          <ul class="top-menu">
            <li><a href="../index.php">HOME</a></li>
            <li><a href="../index.php?action-status=paying">MOST PAYING</a></li>
            <li><a href="best-earner.php">BEST EARNERS</a></li>
          </ul>
        </div>
      </div>
