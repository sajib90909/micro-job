<?php
include '../database/config.php';
include "../database/database.php";
$db = new database();
$dates = new DateTime('now', new DateTimeZone('UTC') );
$dates = $dates->format('Y-m-d H:i:s');
$action_status = 'active';
$page = 1;
function test_input($data) {
    $db = new database();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($db->link,$data);
    return $data;
}
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['worker_ban'])){
    $user_id= test_input($_POST['user_id']);
    $status= test_input($_POST['status']);
    if($status == 'banned'){
      $status = 'active';
    }else{
      $status = 'banned';
    }
    if (isset($_POST["action_status"]) && ($_POST["action_status"] == 'unverified' || $_POST["action_status"] == 'banned')) {
        $action_status = test_input($_POST["action_status"]);
    }
    if (isset($_POST["page"]) && is_numeric($_POST["page"]) && $_POST["page"] > 0) {
        $page = test_input($_POST["page"]);
    }
    $user_update_sql = "UPDATE workers_table SET status = '$status' WHERE user_id = '$user_id' ";
    $user_insert_read = $db -> update($user_update_sql);
    if ($user_insert_read) {
      header('Location: ../workers/index.php?action-status='.$action_status.'&&page='.$page.'&&action=success');
    }else{
      header('Location: ../workers/index.php?action-status='.$action_status.'&&page='.$page.'&&action=error');
    }

}
?>
