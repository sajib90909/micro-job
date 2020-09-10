<?php
extract($_POST);
// if($_SERVER['REQUEST_METHOD']=='POST'){
  header('Content-Type: application/json');
  $data = trim($action);
  $data = strtolower($data);
  IF($data == 'mute'){
    $data = 'Unmute';
  }else{
    $data = 'Mute';
  }
  echo json_encode(['code' => $data, 'index' => '2', 'status' => $job_id, 'mail' => '4']);
// }


?>
