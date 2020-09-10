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
function update_task($task_id,$dev_cmnt,$status,$target,$status_page,$task_rate,$worker_id){
  if(!empty($status)){
    $status_change = false;
    $stetment = false;
    $db = new database();
    if($status_page == 'check'){
      $stetment = true;
      $check_update_sql = "SELECT status FROM task_table WHERE id = '$task_id'";
      $check_update_read = $db->select($check_update_sql);
      if($check_update_read){
        $check_update_row = $check_update_read->fetch_assoc();
        $pev_status = $check_update_row['status'];
        if($pev_status != $status){
          $status_change = true;
        }
      }
    }
    if(!$stetment || $status_change){
      $task_update_sql = "UPDATE task_table SET status = '$status', dev_comment = '$dev_cmnt' WHERE id = '$task_id' ";
      $task_insert_read = $db -> update($task_update_sql);
      if ($task_insert_read) {
          if($status == 'accept' || $status_change){
            $workers_blnc_sql = "SELECT balance,total_earn FROM workers_table WHERE user_id = '$worker_id'";
            $workers_blnc_read = $db->select($workers_blnc_sql);
            if($workers_blnc_read){
              $workers_blnc_row = $workers_blnc_read->fetch_assoc();
              $workers_blnc = $workers_blnc_row['balance'];
              $workers_total_e = $workers_blnc_row['total_earn'];
              if($status_change){
                if($status == 'accept'){
                    $updt_blnc = $workers_blnc + $task_rate;
                    $updt_t_e = $workers_total_e + $task_rate;
                  }else{
                    $updt_blnc = $workers_blnc - $task_rate;
                    $updt_t_e = $workers_total_e - $task_rate;
                  }
              }else{
                $updt_blnc = $workers_blnc + $task_rate;
                $updt_t_e = $workers_total_e + $task_rate;
              }
              $worker_tb_sql = "UPDATE workers_table SET balance = '$updt_blnc', total_earn = '$updt_t_e' WHERE user_id = '$worker_id' ";
              $worker_tb_read = $db -> update($worker_tb_sql);
              if ($worker_tb_read) {
                }else{
                  
                }

            }
          }
        }else{
          header('Location: ../authorities/work-check.php?target='.$target.'&&status='.$status_page.'&&action=error');
        }
    }





    // ---------------

  }

}
if($_SERVER['REQUEST_METHOD']=='POST'){
    $total_task = count($_POST['task_id']);
    $status_page= test_input($_POST['status_page']);
    $task_rate= test_input($_POST['task_rate']);
    $target= test_input($_POST['target']);
    for($i = 0 ; $i < $total_task; $i++){
      $task_id = test_input($_POST['task_id'][$i]);
      $dev_cmnt = test_input($_POST['dev_cmnt_'.$task_id]);
      $status = test_input($_POST['status_'.$task_id]);
      $worker_id= test_input($_POST['worker_id_'.$task_id]);
      update_task($task_id,$dev_cmnt,$status,$target,$status_page,$task_rate,$worker_id);
    }
    header('Location: ../authorities/work-check.php?target='.$target.'&&status='.$status_page.'&&action=success');

}
?>
