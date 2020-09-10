<?php include '../inc/header.php';?>
<?php
$update_action = 'basic';
if (isset($_GET['update']) && ($_GET['update'] == "name" || $_GET['update'] == "password" || $_GET['update'] == "email" || $_GET['update'] == "image")) { // logout and destroy all session
    $update_action = $_GET['update'];
}

?>
      <div class="section-four content-width">
        <?php include '../inc/profile-menu.php';?>
        <div style="padding:10px 0 0; margin-top: 10px;border-top:1px solid green;" class="btn-group-sec" role="group" aria-label="...">
          <a href="edit-profile.php" class="action-btn btn-light">basic</a>
          <a href="?update=name" class="action-btn btn-light">Change Name</a>
          <a href="?update=password" class="action-btn btn-light">Change Password</a>
          <a href="?update=email" class="action-btn btn-light">change Email</a>
          <a href="?update=image" class="action-btn btn-light">change Image</a>
        </div>
        <div class="card-header-sec">
          <label>
            <?php
            if($update_action == 'name'){
              echo 'Update Name';
            }elseif($update_action == 'password'){
              echo 'Update Password';
            }elseif($update_action == 'email'){
              echo 'Update Email';
            }elseif($update_action == 'image'){
              echo 'Update Image';
            }else{
              echo 'Basic Information';
            }
            ?>
            </label>
        </div>
        <div class="input-sec">
          <?php
          if(isset($_SESSION['login']) && isset($_SESSION['user_id']) && $_SESSION['login'] == true){
            $user_id = $_SESSION['user_id'];
            $worker_query = "SELECT * FROM workers_table WHERE user_id = '$user_id'";
            $worker_read = $db->select($worker_query);
            if ($worker_read) {
                $count_job = mysqli_num_rows($worker_read);
                if($count_job > 0){
                  $worker_row = $worker_read->fetch_assoc();
                  $work_title = $worker_row['name'];
                  $user_phone = $worker_row['phone'];
                  $user_email = $worker_row['email'];
                  $user_occupation = $worker_row['occupation'];
                  $user_company = $worker_row['company'];
                  $user_address = $worker_row['address'];
                  $user_gender = $worker_row['gender'];
                  $user_birthdate = $worker_row['birthdate'];
                  $user_zip_code = $worker_row['zip_code'];
                  $user_status = $worker_row['status'];
                  $user_city = $worker_row['city'];
                  $user_img = $worker_row['img_url'];
                  $user_nid = $worker_row['nid'];

          if($update_action == 'name'){ ?>
            <form class="form-horizontal" action="../func/up-func.php" method="post">
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">Current Name:</label>
                <div class="col-sm-8">
                  <label for="inputPassword" class="lebel-class"><?php echo $work_title;?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">New Name:</label>
                <div class="col-sm-8">
                  <input required type="text" class="input-class" name="name" placeholder="Enter Phone Number">
                </div>
              </div>
              <div style="text-align:center; padding-top:20px;" class="form-group">
                <button type="submit" name="update_name" class="btn btn-info mb-2 btn-sm">Update</button>
              </div>
            </form>
          <?php }elseif($update_action == 'password'){ ?>
            <form class="form-horizontal" action="../func/up-func.php" method="post">
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">Current Password:</label>
                <div class="col-sm-8">
                  <input required type="password" class="input-class" name="password" placeholder="Enter Phone Number">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">new password:</label>
                <div class="col-sm-8">
                  <input required type="password" class="input-class" name="n_password" placeholder="Enter Phone Number">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">Confirm Password:</label>
                <div class="col-sm-8">
                  <input required type="password" class="input-class" name="c_password" placeholder="Enter Phone Number">
                </div>
              </div>
              <div style="text-align:center; padding-top:20px;" class="form-group">
                <button type="submit" name="update_password" class="btn btn-info mb-2 btn-sm">Update</button>
              </div>
            </form>
          <?php }elseif($update_action == 'email'){ ?>
            <form class="form-horizontal" action="../func/up-func.php" method="post">
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">Current Email:</label>
                <div class="col-sm-8">
                  <label for="inputPassword" class="lebel-class"><?php echo $user_email;?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">New Email:</label>
                <div class="col-sm-8">
                  <input required type="email" class="input-class" name="email" placeholder="Enter Phone Number">
                </div>
              </div>

              <div style="text-align:center; padding-top:20px;" class="form-group">
                <button type="submit" name="update_email" class="btn btn-info mb-2 btn-sm">Update</button>
              </div>
            </form>
          <?php }elseif($update_action == 'image'){ ?>
            <form class="form-horizontal" action="../func/up-func.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <div class="img-sec">
                  <img src="../user_img/<?php echo $user_img;?>" alt="">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-3 lebel-class">Change</label>
                <div class="col-sm-9">
                  <input required type="file" class="input-class" name="img_url">
                  <label for="inputPassword" class="lebel-class">Image size must be less than 500KB</label>
                </div>
              </div>
              <div style="text-align:center; padding-top:20px;" class="form-group">
                <button type="submit" name="update_image" class="btn btn-info mb-2 btn-sm">Update</button>
              </div>
            </form>
          <?php }else{ ?>
            <form class="form-horizontal" action="../func/up-func.php" method="post">
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-3 lebel-class">Gender</label>
                <div class="col-sm-9">
                  <select class="input-class" name="gender" id="exampleFormControlSelect1">
                    <option value="0">Select Gender</option>
                    <option <?php if($user_gender == 'male'){echo 'selected';}?> value="male">Male</option>
                    <option <?php if($user_gender == 'female'){echo 'selected';}?> value="female">Female</option>
                    <option <?php if($user_gender == 'others'){echo 'selected';}?> value="others">Others</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-3 lebel-class">Birth date</label>
                <div class="col-sm-9">
                  <input required type="date" class="input-class" value="<?php echo $user_birthdate;?>" name="birthdate">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-3 lebel-class">Occupation</label>
                <div class="col-sm-9">
                  <input required type="text" class="input-class" name="occupation" value="<?php echo $user_occupation;?>" placeholder="Enter Phone Number">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-3 lebel-class">Company</label>
                <div class="col-sm-9">
                  <input required type="text" class="input-class" name="company" value="<?php echo $user_company;?>" placeholder="Enter Company Name">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-3 lebel-class">Address</label>
                <div class="col-sm-9">
                  <input required type="text" class="input-class" name="address" value="<?php echo $user_address;?>" placeholder="Enter Address">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-3 lebel-class">Zip Code</label>
                <div class="col-sm-9">
                  <input required type="text" class="input-class" name="zip_code" value="<?php echo $user_zip_code;?>" placeholder="Enter Zip Code">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-3 lebel-class">City</label>
                <div class="col-sm-9">
                  <input required type="text" class="input-class" name="city" value="<?php echo $user_city;?>" placeholder="Enter City">
                </div>
              </div>
              <div style="margin-left:-6px;" class="login-cont ">
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">Phone:</label>
                <div class="col-sm-8">
                  <label for="inputPassword" class="lebel-class"><?php echo $user_phone;?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class"> Change Phone</label>
                <div class="col-sm-8">
                  <input type="number" class="input-class" name="phone" placeholder="Enter Phone Number">
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">Nid:</label>
                <div class="col-sm-8">
                  <label for="inputPassword" class="lebel-class"><?php echo $user_nid;?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 lebel-class">Change Nid</label>
                <div class="col-sm-8">
                  <input type="number" class="input-class" name="nid" placeholder="Enter Nid no.">
                </div>
              </div>
            </div>
              <div style="text-align:center; padding-top:20px;" class="form-group">
                <button type="submit" name="update_basic" class="btn btn-info mb-2 btn-sm">Update</button>
              </div>
            </form>

          <?php }
        }
      }
    }
          ?>
        </div>
        <?php if (isset($_GET['update-status']) && $_GET['update-status'] == 'success'){ ?>
          <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:#20DF2E" class="section-five">
            <div class="login-head">
              <h6>Update succefull. </h6>
            </div>
          </div>
        <?php } ?>
        <?php if (isset($_GET['update-status']) && $_GET['update-status'] == 'notmatch'){ ?>
          <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:red" class="section-five">
              <h6>Password is not match! </h6>
          </div>
        <?php } ?>
        <?php if (isset($_GET['update-status']) && $_GET['update-status'] == 'error'){ ?>
          <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:red" class="section-five">
              <h6>Failed to submit your work. please try again </h6>
          </div>
        <?php } ?>
        <?php if (isset($_GET['update-status']) && $_GET['update-status'] == 'exits'){ ?>
          <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:red" class="section-five">
              <h6>Already exits. please try another or contact with authorities! </h6>
          </div>
        <?php } ?>
        <?php if (isset($_GET['update-status']) && $_GET['update-status'] == 'empty'){ ?>
          <div style="margin-bottom:30px;padding:20px;background-color:#fff;color:red" class="section-five">
              <h6>Fill all input field !</h6>
          </div>
        <?php } ?>

      </div>
<?php include '../inc/footer.php';?>
