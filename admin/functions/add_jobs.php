<?php
include '../database/config.php';
include "../database/database.php";
$db = new database();
$dates = new DateTime('now', new DateTimeZone('UTC') );
$dates = $dates->format('Y-m-d H:i:s');
$action_status = 'publish';
$page = 1;
function test_input($data) {
    $db = new database();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($db->link,$data);
    return $data;
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    $job_name= test_input($_POST['job_name']);
    $provider= test_input($_POST['provider']);
    $rate= test_input($_POST['rate']);
    $total_count= test_input($_POST['total_count']);
    $level= test_input($_POST['level']);
    $end_date= test_input($_POST['end_date']);
    $details = test_input($_POST['work_details']);
    $proves= test_input($_POST['required_proves']);
    $status = "publish";
    $assign_date = $dates;
    if(isset($_POST['update_job_post'])){
      $action = 'update';
      $job_id= test_input($_POST['job_id']);
      $status = test_input($_POST['status']);
    }elseif (isset($_POST['add_job_post'])) {
      $action = 'add';
    }else{
      header('Location: ../authorities/');
    }
    if(empty($job_name) || empty($provider) || empty($rate) || empty($total_count) || empty($level) || empty($details) || empty($proves) || empty($status)){

        header('Location: ../authorities/index.php?action=error');

    }else{
      if($action == 'update'){
        if (isset($_POST["action_status"]) && ($_POST["action_status"] == 'mute' || $_POST["action_status"] == 'complete')) {
            $action_status = test_input($_POST["action_status"]);
        }
        if (isset($_POST["page"]) && is_numeric($_POST["page"]) && $_POST["page"] > 0) {
            $page = test_input($_POST["page"]);
        }
        $user_update_sql = "UPDATE job_table SET job_name = '$job_name', provider = '$provider', rate = '$rate', total_count = '$total_count',
         level = '$level', end_date = '$end_date', details = '$details', proves = '$proves', status = '$status' WHERE id = '$job_id' ";
        $user_insert_read = $db -> update($user_update_sql);
      }elseif ($action == 'add') {
        $user_insert_sql = "INSERT INTO job_table (job_name, provider, rate, total_count, level, end_date, assign_date, details, proves, status)
          VALUES ('$job_name', '$provider', '$rate', '$total_count', '$level', '$end_date', '$assign_date', '$details', '$proves', '$status')";
        $user_insert_read = $db -> insert($user_insert_sql);
      }else{
        header('Location: ../authorities/index.php?action-status='.$action_status.'&&page='.$page.'&&action=error');
      }
      if ($user_insert_read) {
        header('Location: ../authorities/index.php?action-status='.$action_status.'&&page='.$page.'&&action=success');
      }else{
        header('Location: ../authorities/index.php?action-status='.$action_status.'&&page='.$page.'&&action=error');
      }
    }

}
?>
