<?php
include '../database/config.php';
include "../database/database.php";
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
    $name= test_input($_POST['name']);
    $phone= test_input($_POST['phone']);
    $email= test_input($_POST['email']);
    $gender= test_input($_POST['gender']);
    $birthdate= test_input($_POST['birthdate']);
    $f_password= test_input($_POST['password']);
    $c_password= test_input($_POST['confirm_password']);
    $occupation= test_input($_POST['occupation']);
    $company = test_input($_POST['company']);
    $address= test_input($_POST['address']);
    $zip_code= test_input($_POST['zip_code']);
    $img_url= test_input($_FILES['img_url']['name']);
    $city= test_input($_POST['city']);
    $nid = test_input($_POST['nid']);
    $status = "unverified";
    $reg_date = $dates;
    $user_ip = '0';
    $level = 1;
    $password = md5($f_password);
    if($f_password != $c_password){
      $proccesing = false;
      $report .= 'Confirm password is not matched.'.'<br>';
    }
    if(isset($_POST['update_job_post'])){
      $action = 'update';
      $job_id= test_input($_POST['job_id']);
      $status = test_input($_POST['status']);
    }elseif (isset($_POST['reg_req'])) {
      $action = 'add';
    }else{
      $proccesing = false;
      $report .= 'wrong try'.'<br>';
    }
    if(empty($name) || empty($phone) || empty($email) || $gender == '0' || empty($birthdate) || empty($password) || empty($occupation) || empty($company) || empty($address) || empty($zip_code) || empty($city) || empty($nid)){
        echo $name.'--'.$phone.'--'.$email.'--'.$gender.'--'.$birthdate.'--'.$password.'--'.$occupation.'--'.$company.'--'.$address.'--'.$zip_code.'--'.$city.'--'.$nid;
        header('Location: ../system/registration.php?action= input all field');

    }else{
    // ---------------create user_id--------------
    // ---------------------------------------------------------cadet user id ----
    if($action == 'add'){
      $find_dis_sql = "SELECT * FROM workers_table WHERE phone = '$phone' OR email = '$email' OR nid = '$nid'";
      $find_dis_read = $db->select($find_dis_sql);
      if($find_dis_read){
        $count_dis = mysqli_num_rows($find_dis_read);
        if ($count_dis > 0) {
          $proccesing = false;
          $report .= 'your data is already exits'.'<br>';
        }else{
          $last_user_sql = "SELECT * FROM token_table WHERE user = 'user_id' ";
          $last_user_read = $db->select($last_user_sql);
          if($last_user_read){
            $user_token_row = $last_user_read->fetch_assoc();
            $user_token = $user_token_row['token'];
            $user_token = $user_token + 1;
            $user_token_uptade = "UPDATE token_table SET token = '$user_token' WHERE user = 'user_id' " ;
            $token_uptade_read = $db->update($user_token_uptade);
            if ($token_uptade_read) {
              $user_id = 'user_'.$user_token;
            }
          }else{
            $proccesing = false;
            $report .= 'user id creation failed'.'<br>';
          }
        }
      }
    }

    // --------------create user_id end-----------
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
        if($action == 'add'){
          $img_type = "error";
          $img_message = "Choose image file to upload.";
        }else{
          $img_empty = true;
        }

    }    // Validate file input to check if is with valid extension
    else if (! in_array($file_extension, $allowed_image_extension)) {
            $img_type = "error";
            $img_message = "Upload valiid images. Only PNG, JPG and JPEG are allowed.";
    }    // Validate image file size
    else if (($_FILES["img_url"]["size"] > 500000)) {

            $img_type = "error";
            $img_message = "Image size exceeds 500KB";

    }    // Validate image file dimension
    // else if ($width > "300" || $height > "300") {
    //
    //         $img_type = "error";
    //         $img_message = "Image dimension should be within 300X200";
    //
    // }
     else {
       if ($proccesing) {
         $img_url = $user_id.".".$file_extension;
         $target = "../user_img/" .$img_url;
         if (move_uploaded_file($_FILES["img_url"]["tmp_name"], $target)) {

                 $img_type = "success";
                 $img_message = "Image uploaded successfully.";

         } else {

                 $img_type = "error";
                 $img_message = "Problem in uploading image files.";

         }
       }

    }
    // ------------img functions end----------
    if($action == 'add'){
      function get_client_ip() {
          $ipaddress = '';
          if (isset($_SERVER['HTTP_CLIENT_IP']))
              $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
          else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
              $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
          else if(isset($_SERVER['HTTP_X_FORWARDED']))
              $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
          else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
              $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
          else if(isset($_SERVER['HTTP_FORWARDED']))
              $ipaddress = $_SERVER['HTTP_FORWARDED'];
          else if(isset($_SERVER['REMOTE_ADDR']))
              $ipaddress = $_SERVER['REMOTE_ADDR'];
          else
              $ipaddress = 'UNKNOWN';
          return $ipaddress;
      }

      $user_ip = get_client_ip();
    }

    // -------------ip address func end---------
    // echo $name.'--'.$phone.'--'.$email.'--'.$gender.'--'.$birthdate.'--'.$password.'--'.$occupation.'--'.$company.'--'.$address.'--'.$zip_code.'--'.$city.'--'.$img_url.'--'.$reg_date.'--'.$user_ip;
    if($proccesing && $img_type != "error"){
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
        $user_insert_sql = "INSERT INTO workers_table (user_id, name, phone, email, gender, birthdate, password, occupation,
          company, address, zip_code, city, nid, reg_date, status, user_ip, img_url, level)
          VALUES ('$user_id', '$name', '$phone', '$email', '$gender', '$birthdate', '$password', '$occupation',
            '$company', '$address', '$zip_code', '$city', '$nid', '$reg_date', '$status', '$user_ip', '$img_url', '$level')";
        $user_insert_read = $db -> insert($user_insert_sql);
      }else{
        header('Location: ../system/registration.php?action=insert database error');
      }
      if ($user_insert_read) {
        header('Location: ../system/login.php?registration=success');
      }else{
        header('Location: ../system/registration.php?action=database read error');
      }
    }else{
      if($img_type == "error"){
        $report .= $img_message.'<br>';
      }
      header('Location: ../system/registration.php?action='.$report);
    }

    }

}
?>
