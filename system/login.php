<?php
include '../database/config.php';
include "../database/database.php";
include "../func/session.php";
Session::init(); // session start
function test_input($data) {
    $db = new database();
    $data = trim($data);
    $data = strtolower($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($db->link,$data);
    return $data;
}
$password = false;
$db = new database();
function RandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
<?php
if (isset($_GET["emailtoken"]) && isset($_GET["email"])) {
    $emailtoken = test_input($_GET["emailtoken"]);
    $email_v = test_input($_GET["email"]);
    $query = "SELECT * FROM email_token WHERE email = '$email_v' AND token = '$emailtoken'";
    $read = $db->select($query);
    if ($read) {
        $value = mysqli_fetch_array($read);
        $row = mysqli_num_rows($read);
        if ($row > 0) {
          $userid = $value['user_id'];
          $e_token_update_sql = "DELETE FROM email_token WHERE email = '$email_v' AND token = '$emailtoken'";
          $e_token_insert_read = $db -> delete($e_token_update_sql);
          if ($e_token_insert_read) {
            $e_token_update_sql = "UPDATE workers_table SET status = 'active' WHERE email = '$email_v' AND user_id = '$userid' ";
            $e_token_insert_read = $db -> update($e_token_update_sql);
            if ($e_token_insert_read) {
              header('location:login.php?email_verify=success');
            }
          }
        }
      }
}
if (isset($_GET["resettoken"]) && isset($_GET["email"])) {
    $resettoken = test_input($_GET["resettoken"]);
    $email_p = test_input($_GET["email"]);
    $query = "SELECT * FROM pass_reset WHERE email = '$email_p' AND token = '$resettoken'";
    $read = $db->select($query);
    if ($read) {
        $value = mysqli_fetch_array($read);
        $row = mysqli_num_rows($read);
        if ($row > 0) {
          $password = true;
        }
      }
}
function mailsend($username,$mail,$resettoken,$action){
  $domain = $_SERVER['HTTP_HOST'];
  $domain_protocol= $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
  $from = "account@".$domain;
  // Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers.= "Content-type:text/html;charset=UTF-8" . "\r\n";
  // More headers
  $headers.= 'From: <' . $from . '>' . "\r\n";
  $message = '<html><body>';
  $message .= '<div style="width:100%;text-align:center;">';
  $message .= "<h3>Hi ".$username."</h3>";
  if($action == 'email'){
    $subject = "Verify account";
    $message .= "<p>Micro Job Authorities has received a request to verify your Email.</p>";
    $message .= "<p>If you did not request to Verify your email , please ignore this email.</p>";
    $message .= "<p>For verify email </p>";
    $message .= "<a href='".$domain_protocol."://".$domain."/system/login.php?emailtoken=".$resettoken."&&email=".$mail."'>click here</a>";
  }else{
    $subject = "Reset Password";
    $message .= "<p>Micro Job Authorities has received a request to reset the password for your account.</p>";
    $message .= "<p>If you did not request to reset your password, please ignore this email.</p>";
    $message .= "<p>For Reset Password</p>";
    $message .= "<a href='".$domain_protocol."://".$domain."/system/login.php?resettoken=".$resettoken."&&email=".$mail."'>click here</a>";
  }
  $message .= "</div>";
  $message .= "</body></html>";
  if (mail($mail, $subject, $message, $headers)) {
    return true;
  }
  return true;
}

if (empty($_SESSION['key'])) {
  // $_SESSION['key'] = bin2hex(random_bytes(32)); //generate random token //only run in php7
    $bytes = openssl_random_pseudo_bytes(32, $cstrong);
    $_SESSION['key']   = bin2hex($bytes);
}
?>
<?php
  //check user email and password
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['login'])){


          $email = test_input($_POST['email']);
          $password = test_input($_POST['password']);
          $password = md5($password);
          $query = "SELECT * FROM workers_table WHERE email = '$email' AND password = '$password'";
          $read = $db->select($query);
          if ($read) {
              $value = mysqli_fetch_array($read);
              $row = mysqli_num_rows($read);
              if ($row > 0) {
                $user_status = $value['status'];
                if ($user_status == 'unverified') {
                  Session::set("error", 1);
                  header('location:login.php?login=failed&&user=unverified');
                }elseif($user_status == 'banned'){
                  Session::set("error", 1);
                  header('location:login.php?login=failed&&user=banned');
                }else{
                  Session::set("login", true);
                  Session::set("name", $value['name']); //get user name
                  Session::set("email", $value['email']); //get user email
                  Session::set("user_id", $value['user_id']);
                  header("location:../index.php");
                }

              } else {
                  header('location:login.php?login=failed');
                  Session::set("error", 1);
              }
          }
      }elseif(isset($_POST['email_verify']) && !empty($_POST['email'])){
        $email = test_input($_POST['email']);
        $query = "SELECT * FROM workers_table WHERE email = '$email'";
        $read = $db->select($query);
        $store = false;
        if ($read) {
            $row = mysqli_num_rows($read);
            if ($row > 0) {
              $value = mysqli_fetch_array($read);
              $e_user_id = $value['user_id'];
              $e_user_name = $value['name'];
              $e_bytes = RandomString();
              $query = "SELECT * FROM email_token WHERE user_id = '$e_user_id'";
              $read = $db->select($query);
              if ($read) {
                  $row = mysqli_num_rows($read);
                  if ($row > 0) {
                    $e_token_update_sql = "UPDATE email_token SET token = '$e_bytes' WHERE user_id = '$e_user_id' AND email = '$email' ";
                    $e_token_insert_read = $db -> update($e_token_update_sql);
                    if ($e_token_insert_read) {
                      $store = true;
                    }
                  }else{
                    $user_update_sql = "INSERT INTO email_token (user_id, email, token)
                      VALUES ('$e_user_id', '$email', '$e_bytes')";
                    $user_insert_read = $db -> insert($user_update_sql);
                    if ($user_insert_read) {
                      $store = true;
                    }
                  }
                }
                if($store){
                  if(mailsend($e_user_name,$email,$e_bytes,'email')){
                    header('location:login.php?send_mail=pass');
                  }else{
                    header('location:login.php?action=email&&send_mail=failed');
                  }

                }else{
                  header('location:login.php?action=email&&send_mail=failed');
                }
            }else{
              header('location:login.php?action=email&&send_mail=notmatch');
            }
          }
      }elseif (isset($_POST['confirm_password']) && !empty($_POST['email']) && !empty($_POST['token']) && !empty($_POST['password']) && !empty($_POST['confirm_pass'])) {
        $passtoken = test_input($_POST["token"]);
        $email = test_input($_POST["email"]);
        $password = test_input($_POST["password"]);
        $confirm_pass = test_input($_POST["confirm_pass"]);
        $pass_std= false;
        if($password == $confirm_pass){
          $query = "SELECT * FROM pass_reset WHERE email = '$email' AND token = '$passtoken'";
          $read = $db->select($query);
          if ($read) {
              $value = mysqli_fetch_array($read);
              $row = mysqli_num_rows($read);
              if ($row > 0) {
                $userid = $value['user_id'];
                $password = md5($password);
                $e_token_update_sql = "DELETE FROM pass_reset WHERE email = '$email' AND token = '$passtoken'";
                $e_token_insert_read = $db -> update($e_token_update_sql);
                if ($e_token_insert_read) {
                  $e_token_update_sql = "UPDATE workers_table SET password = '$password' WHERE email = '$email' AND user_id = '$userid' ";
                  $e_token_insert_read = $db -> update($e_token_update_sql);
                  if ($e_token_insert_read) {
                    $pass_std = true;
                  }
                }
              }
            }
        }else{
          header('location:login.php?resettoken='.$resettoken.'&&email='.$mail.'&&password_reset=notmatch');
        }

        if($pass_std){
          header('location:login.php?password_reset=success');
        }else{
          header('location:login.php?password_reset=failed');
        }
      }elseif (isset($_POST['reset_password']) && !empty($_POST['email'])) {
        $email = test_input($_POST['email']);
        $query = "SELECT * FROM workers_table WHERE email = '$email'";
        $read = $db->select($query);
        $store = false;
        if ($read) {
            $row = mysqli_num_rows($read);
            if ($row > 0) {
              $value = mysqli_fetch_array($read);
              $e_user_id = $value['user_id'];
              $e_user_name = $value['name'];
              $e_bytes = RandomString();
              $query = "SELECT * FROM pass_reset WHERE user_id = '$e_user_id'";
              $read = $db->select($query);
              if ($read) {
                  $row = mysqli_num_rows($read);
                  if ($row > 0) {
                    $e_token_update_sql = "UPDATE pass_reset SET token = '$e_bytes' WHERE user_id = '$e_user_id' AND email = '$email' ";
                    $e_token_insert_read = $db -> update($e_token_update_sql);
                    if ($e_token_insert_read) {
                      $store = true;
                    }

                  }else{
                    $user_update_sql = "INSERT INTO pass_reset (user_id, email, token)
                      VALUES ('$e_user_id', '$email', '$e_bytes')";
                    $user_insert_read = $db -> insert($user_update_sql);
                    if ($user_insert_read) {
                      $store = true;
                    }
                  }
                }
                if($store){
                  if(mailsend($e_user_name,$email,$e_bytes,'password')){
                    header('location:login.php?send_mail=pass');
                  }else{
                    header('location:login.php?action=password&&send_mail=failed');
                  }

                }else{
                  header('location:login.php?action=password&&send_mail=failed');
                }
            }else{
              header('location:login.php?action=password&&send_mail=notmatch');
            }
          }

      }
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
    <div class="warrper login-page">
      <?php if (isset($_GET['registration']) && $_GET['registration'] == 'success'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:#20DF2E" class="section-five">
          <div class="login-head">
            <h6>Your Registration is successfull. please login after email verification! email has been sent. </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['email_verify']) && $_GET['email_verify'] == 'success'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;" class="section-five">
          <div class="login-head">
            <h6>Email verification successfull! now you have access. login please! </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['email_verify']) && $_GET['email_verify'] == 'failed'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;" class="section-five">
          <div class="login-head">
            <h6 style="color:red">Email verification failed! </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['password_reset']) && $_GET['password_reset'] == 'success'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;" class="section-five">
          <div class="login-head">
            <h6>Resset your password now! </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['password_reset']) && $_GET['password_reset'] == 'failed'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;" class="section-five">
          <div class="login-head">
            <h6 style="color:red">Password reset failed! </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['password_reset']) && $_GET['password_reset'] == 'notmatch'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;" class="section-five">
          <div class="login-head">
            <h6 style="color:red">Confirm password not match! </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['send_mail']) && $_GET['send_mail'] == 'failed'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;" class="section-five">
          <div class="login-head">
            <h6 style="color:red">SMS send failed try again letar!! </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['send_mail']) && $_GET['send_mail'] == 'pass'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;" class="section-five">
          <div class="login-head">
            <h6>We have send a mail. please check your email! may need to wait maximum 5 minutes </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['send_mail']) && $_GET['send_mail'] == 'notmatch'){ ?>
        <div style="margin-bottom:30px;padding:20px;background-color:#fff;" class="section-five">
          <div class="login-head">
            <h6 style="color:red">Yor email is not match! </h6>
          </div>
        </div>
      <?php } ?>
      <?php if (isset($_GET['login']) && $_GET['login'] == 'failed'){ ?>
        <?php if (isset($_GET['user']) && $_GET['user'] == 'unverified'){ ?>
          <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:#FF9A18" class="section-five">

              <h6>Your email address is not verified. Please check your email. If you dont recive email than <a class="forget-sec" href="?action=email">click here</a>  </h6>

          </div>
      <?php }elseif (isset($_GET['user']) && $_GET['user'] == 'banned'){ ?>
          <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:red" class="section-five">

              <h6>You are banned for your illagal activities. If you have any questions please contact with authorities.. <a class="forget-sec" href="#">click here</a>  </h6>

          </div>
      <?php }else{ ?>
          <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:red" class="section-five">

              <h6>Your password or email address is not correct. please try again! </h6>

          </div>
        <?php } ?>

      <?php } ?>


      <div class="section-five">
        <div class="login-head">
          <h4>MICRO-JOB</h4>
        </div>
        <?php if($password){ ?>
            <form class="" action="login.php" method="post">
            <div class="login-cont">
              <div class="singup-link">
                Reset Password
              </div>
              <div class="">
                <input hidden required type="email" class="login-input" name="email" aria-label="Username" value="<?php echo $email_p ?>" aria-describedby="basic-addon1">
                <input hidden required type="text" class="login-input" name="token" aria-label="Username" value="<?php echo $resettoken ?>" aria-describedby="basic-addon1">
                <input required type="password" class="login-input" name="password" placeholder="Enter new password" aria-label="Username" aria-describedby="basic-addon1">
                <input required type="password" class="login-input" name="confirm_pass" placeholder="Confirm new Email" aria-label="Username" aria-describedby="basic-addon1">
              </div>
            </div>

            <div style="padding-bottom:30px;" class="log-in-btn-sec">
              <button type="submit" name="confirm_password" class="log-in-btn btn btn-success btn-sm" name="button">Submit</button>
            </div>
            <div class="singup-link">
              <a href="login.php">BACK</a>
            </div>
          </form>
        <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'email'){ ?>
          <form class="" action="login.php" method="post">
          <div class="login-cont">
            <div class="singup-link">
              Email verify
            </div>
            <div class="">
              <input required type="email" class="login-input" name="email" placeholder="Enter your Email" aria-label="Username" aria-describedby="basic-addon1">
            </div>
          </div>

          <div style="padding-bottom:30px;" class="log-in-btn-sec">
            <button type="submit" name="email_verify" class="log-in-btn btn btn-success btn-sm" name="button">Send SMS</button>
          </div>
          <div class="singup-link">
            <a href="login.php">BACK</a>
          </div>
        </form>
      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'password'){ ?>
        <form class="" action="login.php" method="post">
        <div class="login-cont">
          <div class="singup-link">
            Reset Password
          </div>
          <div class="">
            <input required type="email" class="login-input" name="email" placeholder="Enter your Email" aria-label="Username" aria-describedby="basic-addon1">
          </div>
        </div>

        <div style="padding-bottom:30px;" class="log-in-btn-sec">
          <button type="submit" name="reset_password" class="log-in-btn btn btn-success btn-sm" name="button">Send SMS</button>
        </div>
        <div class="singup-link">
          <a href="login.php">BACK</a>
        </div>
      </form>
        <?php }else{ ?>
          <form class="" action="login.php" method="post">
          <div class="login-cont">
            <div class="">
              <input required type="email" class="login-input" name="email" placeholder="Email" aria-label="Username" aria-describedby="basic-addon1">
              <input required type="password" class="login-input" name="password" aria-label="Username" placeholder="Password" aria-describedby="basic-addon1">
            </div>
            <div class="forget-sec">
              <a href="?action=password">Forgot Password...</a>
            </div>
          </div>

          <div class="log-in-btn-sec">
            <button type="submit" name="login" class="log-in-btn btn btn-success btn-sm" name="button">SIGN IN</button>
          </div>
        </form>
          <div class="singup-sec">
            <div class="signup-label">
              <p>Don't you have any ID?</p>
            </div>
            <div class="singup-link">
              <a href="registration.php">SIGN UP NOW</a>
            </div>
          </div>
        <?php }?>
      </div>
      <div class="footer">
        <label>Copyright @ 2019</label>
      </div>
    </div>
  </body>
</html>
