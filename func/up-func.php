<?php
include '../database/config.php';
include "../database/database.php";
include "../func/session.php";
Session::init(); // session start
$db = new database();
$dates = new DateTime('now', new DateTimeZone('UTC') );
$dates = $dates->format('Y-m-d H:i:s');
$action_status = 'publish';
$page = 1;
$img_empty = false;
$proccesing = true;
$report = '';

function test_input($data) {
    $db = new database();
    $data = trim($data);
    $data = strtolower($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($db->link,$data);
    return $data;
}
if($_SERVER['REQUEST_METHOD']=='POST'){
  $user_id = $_SESSION['user_id'];
//------------------------------------------------name---------------------------------------------------
  if(isset($_POST['update_name'])){
    $name= test_input($_POST['name']);
    if(!empty($name)){
      $user_update_sql = "UPDATE workers_table SET name = '$name' WHERE user_id = '$user_id' ";
      $user_insert_read = $db -> update($user_update_sql);
      if ($user_insert_read) {
        header('Location: ../system/edit-profile.php?update=name&&update-status=success');
      }else{
        header('Location: ../system/edit-profile.php?update=name&&update-status=error');
      }
    }else{
      header('Location: ../system/edit-profile.php?update=name&&update-status=error');
    }
  }
//------------------------------------------------password---------------------------------------------------
  elseif(isset($_POST['update_password'])){
    $password= test_input($_POST['password']);
    $n_password= test_input($_POST['n_password']);
    $c_password= test_input($_POST['c_password']);
    if($n_password != $c_password){
      header('Location: ../system/edit-profile.php?update=password&&update-status=notmatch');
    }else{
      $n_password = md5($n_password);
      $password = md5($password);
      if(!empty($n_password)){
        $worker_query = "SELECT * FROM workers_table WHERE password = '$password' AND user_id = '$user_id'";
        $worker_read = $db->select($worker_query);
        if ($worker_read) {
            $count_job = mysqli_num_rows($worker_read);
            if($count_job > 0){
              $user_update_sql = "UPDATE workers_table SET password = '$n_password' WHERE user_id = '$user_id' ";
              $user_insert_read = $db -> update($user_update_sql);
              if ($user_insert_read) {
                header('Location: ../system/edit-profile.php?update=password&&update-status=success');
              }else{
                header('Location: ../system/edit-profile.php?update=password&&update-status=error');
              }
            }else{
              header('Location: ../system/edit-profile.php?update=password&&update-status=notmatch');
            }
          }
      }else{
        header('Location: ../system/edit-profile.php?update=password&&update-status=error');
      }
    }
  }
//------------------------------------------------email---------------------------------------------------
  elseif(isset($_POST['update_email'])){
    $email= test_input($_POST['email']);
    if(!empty($email)){
      $worker_query = "SELECT * FROM workers_table WHERE email = '$email'";
      $worker_read = $db->select($worker_query);
      if ($worker_read) {
          $count_job = mysqli_num_rows($worker_read);
          if($count_job <= 0){
            $user_update_sql = "UPDATE workers_table SET email = '$email' WHERE user_id = '$user_id' ";
            $user_insert_read = $db -> update($user_update_sql);
            if ($user_insert_read) {
              header('Location: ../system/edit-profile.php?logout=target&&update=email&&update-status=success');
            }else{
              header('Location: ../system/edit-profile.php?update=email&&update-status=error');
            }
          }else{
              header('Location: ../system/edit-profile.php?update=email&&update-status=exits');
          }
        }else{
            header('Location: ../system/edit-profile.php?update=email&&update-status=error');
        }

    }else{

    }
  }
//------------------------------------------------image---------------------------------------------------
  elseif(isset($_POST['update_image'])){

    $img_url= test_input($_FILES['img_url']['name']);
    if(!empty($img_url)){
      // ---img functions---
      // Get Image Dimension
      $fileinfo = @getimagesize($_FILES["img_url"]["tmp_name"]);
      $width = $fileinfo[0];
      $height = $fileinfo[1];
      $img_type = "true";

      $allowed_image_extension = array(
          "png",
          "jpg",
          "jpeg"
      );
      // Get image file extension
      $file_extension = pathinfo($_FILES["img_url"]["name"], PATHINFO_EXTENSION);

      // Validate file input to check if is not empty
      if (! file_exists($_FILES["img_url"]["tmp_name"])) {
            $img_type = "error";
            $img_message = "Choose image file to upload.";

      }    // Validate file input to check if is with valid extension
      else if (! in_array($file_extension, $allowed_image_extension)) {
              $img_type = "error";
              $img_message = "Upload valiid images. Only PNG, JPG and JPEG are allowed.";
      }    // Validate image file size
      else if (($_FILES["img_url"]["size"] > 500000)) {

              $img_type = "error";
              $img_message = "Image size exceeds 500KB";

      }else {
           $img_url = $user_id.".".$file_extension;
           $target = "../user_img/" .$img_url;
           $worker_query = "SELECT * FROM workers_table WHERE user_id = '$user_id'";
           $worker_read = $db->select($worker_query);
           if ($worker_read) {
               $count_job = mysqli_num_rows($worker_read);
               if($count_job > 0){
                 $worker_row = $worker_read->fetch_assoc();
                 $user_image_url = $worker_row['img_url'];
                 if(!empty($user_image_url)){
                   unlink('../user_img/'.$user_image_url);
                 }
                 if (move_uploaded_file($_FILES["img_url"]["tmp_name"], $target)) {

                   $user_update_sql = "UPDATE workers_table SET img_url = '$img_url' WHERE user_id = '$user_id' ";
                   $user_insert_read = $db -> update($user_update_sql);
                   if ($user_insert_read) {
                     $img_type = "success";
                     $img_message = "Image uploaded successfully.";
                   }else{
                     $img_type = "error";
                     $img_message = "Problem in uploading database files.";
                   }



                 } else {

                         $img_type = "error";
                         $img_message = "Problem in uploading image files.";

                 }
               }
             }


      }
      header('Location: ../system/edit-profile.php?update=image&&update-status='.$img_type);

    }else{
      header('Location: ../system/edit-profile.php?update=image&&update-status=empty');
    }

  }
//------------------------------------------------basic---------------------------------------------------
  elseif(isset($_POST['update_basic'])){
    $phone= test_input($_POST['phone']);
    $gender= test_input($_POST['gender']);
    $birthdate= test_input($_POST['birthdate']);
    $occupation= test_input($_POST['occupation']);
    $company = test_input($_POST['company']);
    $address= test_input($_POST['address']);
    $zip_code= test_input($_POST['zip_code']);
    $city= test_input($_POST['city']);
    $nid = test_input($_POST['nid']);
    $basic_exits = false;
    $phone_check = false;
    $nid_check = false;
    if(!empty($gender) && !empty($birthdate) && !empty($occupation) && !empty($company) && !empty($address) && !empty($zip_code) && !empty($city)){
            if(!empty($phone)){
              $worker_query = "SELECT * FROM workers_table WHERE phone = '$phone' ";
              $worker_read = $db->select($worker_query);
              if ($worker_read) {
                  $count_job = mysqli_num_rows($worker_read);
                  if($count_job > 0){
                    $basic_exits = true;
                  }else{
                    $phone_check = true;
                  }
                }
            }
            if(!empty($nid)){
              $worker_query = "SELECT * FROM workers_table WHERE nid = '$nid' ";
              $worker_read = $db->select($worker_query);
              if ($worker_read) {
                  $count_job = mysqli_num_rows($worker_read);
                  if($count_job > 0){
                    $basic_exits = true;
                  }else{
                    $nid_check = true;
                  }
                }
            }
            if(!$basic_exits){
              $user_update_sql = "UPDATE workers_table SET gender = '$gender'";
              if($phone_check){
                $user_update_sql .= ", phone = '".$phone."'";
              }
              if($nid_check){
                $user_update_sql .= ", nid = '".$nid."'";
              }
              $user_update_sql .= ", birthdate = '".$birthdate."', occupation = '".$occupation."', company = '".$company."', address = '".$address."', zip_code = '".$zip_code."', city = '".$city."' WHERE user_id = '".$user_id."'";
              $user_insert_read = $db -> update($user_update_sql);
              if ($user_insert_read) {
                header('Location: ../system/edit-profile.php?update=basic&&update-status=success');
              }else{
                header('Location: ../system/edit-profile.php?update=basic&&update-status=error');
              }
            }else{
              header('Location: ../system/edit-profile.php?update=basic&&update-status=exits');
            }


    }else{
        header('Location: ../system/edit-profile.php?update=basic&&update-status=empty');
    }
  }
// --------------------------------------work-submmit-------------------
  elseif (isset($_POST['work_submit'])) {
    $job_id= test_input($_POST['job_id']);
    $work_details = test_input($_POST['work_details']);
    $total_file = count($_FILES['ss_file']['name']);
    $img_type = "success";
    $details_type = "error";
    $ss_url = '';
    $img_check_std = true;
    if(!empty($work_details)){
    if($total_file > 0 && $total_file <= 3 && !empty($_FILES['ss_file']['name'][0])){
    $img_check_std = false;
    for ($i=0; $i < $total_file ; $i++) {
      $fileinfo = @getimagesize($_FILES["ss_file"]["tmp_name"][$i]);
      $width = $fileinfo[0];
      $height = $fileinfo[1];
      $img_type = "true";
      $allowed_image_extension = array(
          "png",
          "jpg",
          "jpeg"
      );
      // Get image file extension
      $file_extension = pathinfo($_FILES["ss_file"]["name"][$i], PATHINFO_EXTENSION);

      // Validate file input to check if is not empty
      if (! file_exists($_FILES["ss_file"]["tmp_name"][$i])) {
            $img_type = "error";
            $img_message = "Choose image file to upload.";

      }    // Validate file input to check if is with valid extension
      else if (! in_array($file_extension, $allowed_image_extension)) {
              $img_type = "error";
              $img_message = "Upload valiid images. Only PNG, JPG and JPEG are allowed.";
      }    // Validate image file size
      else if (($_FILES["ss_file"]["size"][$i] > 2000000)) {

              $img_type = "error";
              $img_message = "Image size exceeds 2MB";

      }else {
           $ss_url = $job_id.'-'.$user_id.'-'.$i.".".$file_extension;
           $target = "../work_ss/" .$ss_url;
           if (move_uploaded_file($_FILES["ss_file"]["tmp_name"][$i], $target)) {
             $user_update_sql = "INSERT INTO ss_table (job_id, worker_id, ss_url)
               VALUES ('$job_id', '$user_id', '$ss_url')";
             $user_insert_read = $db -> insert($user_update_sql);
             if ($user_insert_read) {
               $img_type = "success";
               $img_message = "Image uploaded successfully.";
               $img_check_std = true;
             }else{
               $img_type = "error";
               $img_message = "Problem in uploading database files.";
             }

           } else {
             $img_type = "error";
             $img_message = "Problem in uploading image files.";

           }


      }
  }
}
if($img_check_std){
  $work_submit_sql = "INSERT INTO task_table (job_id, worker_id, work_details, status,assign_date)
    VALUES ('$job_id', '$user_id', '$work_details', 'uncheck', '$dates')";
  $work_submit_read = $db -> insert($work_submit_sql);
  if ($work_submit_read) {
    $details_type = "success";
  }else{
    $details_type = "error";
  }
}
      if($total_file > 3){
        header('Location: ../system/work-details.php?target='.$job_id.'&&insert-status=img-error');
      }elseif($img_type == "error"){
        header('Location: ../system/work-details.php?target='.$job_id.'&&insert-status=img-error'.$img_message."-----".$total_file);
      }elseif ($details_type == "error") {
        header('Location: ../system/work-details.php?target='.$job_id.'&&insert-status=error');
      }else{
        header('Location: ../system/work-details.php?target='.$job_id.'&&insert-status=success');
      }
  }else{
    header('Location: ../system/work-details.php?target='.$job_id.'&&insert-status=empty');
  }
}
//------------------------------------------------end---------------------------------------------------
  else{
    header('Location: ../index.php');
  }
}
?>
