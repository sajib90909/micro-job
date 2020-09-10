<?php
include '../database/config.php';
include "../database/database.php";
include "../func/session.php";
Session::init(); // session start
$db = new database();
$dates = new DateTime('now', new DateTimeZone('UTC') );
$dates = $dates->format('Y-m-d H:i:s');
function test_input($data) {
    $db = new database();
    $data = trim($data);
    $data = strtolower($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($db->link,$data);
    return $data;
}
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['payment_req'])){
  $user_id = $_SESSION['user_id'];
  $std = false;
  $std_report = '';

//------------------------------------------------name---------------------------------------------------
  if(isset($_POST['payment_req'])){
    $amount_req = test_input($_POST['amount_req']);
    $method = test_input($_POST['method']);
    $account_no = test_input($_POST['account_no']);
    $password = test_input($_POST['password']);
    $password = md5($password);
    // echo $amount_req.'----'.$method.'----'.$account_no.'-----'.$password.'-----'.$user_id;
    if(!empty($amount_req) && !empty($method) && !empty($account_no) && !empty($password) && is_numeric($amount_req) && $amount_req != 0){
      $worker_query = "SELECT * FROM workers_table WHERE user_id = '$user_id' AND password = '$password'";
      $worker_read = $db->select($worker_query);
      if ($worker_read) {
          $count_job = mysqli_num_rows($worker_read);
          if($count_job > 0){
            $worker_row = $worker_read->fetch_assoc();
            $balance = $worker_row['balance'];
            $user_pass = $worker_row['password'];
            $user_withdraw = $worker_row['withdraw'];
            $new_withdraw = $user_withdraw + $amount_req;
            if($balance >= $amount_req){
              $new_balance = $balance - $amount_req;
              $user_update_sql = "UPDATE workers_table SET balance = '$new_balance', withdraw = '$new_withdraw', 	last_withdraw = '$amount_req', 	last_withdraw_date = '$dates' WHERE user_id = '$user_id' ";
              $user_insert_read = $db -> update($user_update_sql);
              $status = 'proccesing';
              if ($user_insert_read) {
                $payment_sql = "INSERT INTO payment_table (user_id, withdraw_req, method, account_no, req_date, status) VALUES ('$user_id', '$amount_req','$method', '$account_no', '$dates','$status')";
                $payment_read = $db -> insert($payment_sql);
                if ($payment_read) {
                  $std = true;
                }else{
                  $std_report = 'data store error. please contact with authority!';
                }
              }else{
                $std_report = 'data store error. please contact with authority!';
              }
            }else{
              $std_report = 'payment request exits your balance !';
            }
          }else{
            $std_report = 'User not found or your password is not match.!';
          }
        }else{
            $std_report = 'User not found. please contact with authority!';
        }

    }else{
      $std_report = 'Input all field.';
    }
    if($std){
      header('Location: ../system/profile.php?request=success');
    }else{
      header('Location: ../system/profile.php?failed='.$std_report);
    }
}
}
?>
