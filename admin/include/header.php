<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Micro Work - Admin</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css"> <!-- bootstrap library call -->
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="../assets/css/all.min.css"> <!-- fontwasome library call -->
</head>

<body>
    <div class="wrapper">
      <div class="action-notify" id="action_notify">
        <?php if(isset($_GET['action']) && $_GET['action'] == 'success'){?>
        <div class="notify_alert alert alert-success" role="alert" id="notify_alert">
          Action successfully done!
        </div>
      <?php } ?>
      <?php if(isset($_GET['action']) && $_GET['action'] == 'error'){?>
        <div class="notify_alert alert alert-danger" role="alert" id="notify_alert">
          Action failed to exicute!
        </div>
      <?php } ?>
      </div>
      <script>
      $("#action_notify").hide().slideDown(1500);
      $("#action_notify").animate({width: 'toggle'}, 1500);
      </script>
