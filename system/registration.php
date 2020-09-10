<!DOCTYPE html>
<?php
include "../func/session.php";
?>
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
    <div class="warrper">
      <div class="secction-one content-width">
        <div class="logo">
          <h4><a href="../index.php">Nirviqit</a></h4>
        </div>
        <div class="btn-group float-right">
          <a href="registration.php" class="action-btn btn-light" name="button">Sign Up</a>
          <a href="login.php" class="action-btn btn-light" name="button">Login</a>
        </div>
      </div>
      <div class="section-two">
        <div class="content-area content-width">
          <ul class="top-menu">
            <li><a href="../index.php">HOME</a></li>
            <li><a href="#">MOST PAYING</a></li>
            <li><a href="best-earner.php">BEST EARNERS</a></li>
          </ul>
        </div>
      </div>
      <div style="padding-top:5px;" class="section-four content-width">
        <div class="card-header-sec">
          <label>Registration</label>
        </div>
        <?php if (isset($_GET['action'])){ ?>
          <div style="border:1px solid red;" class="card-header-sec">
            <label><?php echo $_GET['action'];?></label>
          </div>
        <?php } ?>

        <div class="input-sec">
          <form class="form-horizontal" action="../func/reg-func.php" method="post" enctype="multipart/form-data">
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Name</label>
              <div class="col-sm-9">
                <input required type="text" class="input-class" name="name"  placeholder="Enter name">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Phone</label>
              <div class="col-sm-9">
                <input required type="number" class="input-class" name="phone" placeholder="Enter Phone Number">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Email</label>
              <div class="col-sm-9">
                <input required type="email" class="input-class" name="email" placeholder="Enter Email">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Gender</label>
              <div class="col-sm-9">
                <select class="input-class" name="gender" id="exampleFormControlSelect1">
                  <option value="0">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="others">Others</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Birth date</label>
              <div class="col-sm-9">
                <input required type="date" class="input-class" name="birthdate">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Occupation</label>
              <div class="col-sm-9">
                <input required type="text" class="input-class" name="occupation" placeholder="Enter Phone Number">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Company</label>
              <div class="col-sm-9">
                <input required type="text" class="input-class" name="company" placeholder="Enter Company Name">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Address</label>
              <div class="col-sm-9">
                <input required type="text" class="input-class" name="address" placeholder="Enter Address">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Zip Code</label>
              <div class="col-sm-9">
                <input required type="text" class="input-class" name="zip_code" placeholder="Enter Zip Code">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">City</label>
              <div class="col-sm-9">
                <input required type="text" class="input-class" name="city" placeholder="Enter City">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Nid</label>
              <div class="col-sm-9">
                <input required type="number" class="input-class" name="nid" placeholder="Enter Nid no.">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Image</label>
              <div class="col-sm-9">
                <input required type="file" class="input-class" name="img_url">
                <label for="inputPassword" class="lebel-class">Image size must be less than 500KB</label>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Password</label>
              <div class="col-sm-9">
                <input required type="text" class="input-class" name="password" placeholder="Enter Phone Number">
                <label for="inputPassword" class="lebel-class">Password should be more than 6 Character</label>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-3 lebel-class">Confirm Password</label>
              <div class="col-sm-9">
                <input required type="text" class="input-class" name="confirm_password" placeholder="Enter Phone Number">
              </div>
            </div>
            <div style="text-align:center; padding-top:20px;" class="form-group">
              <button type="submit" name="reg_req" class="btn btn-info mb-2 btn-sm">Confirm</button>
            </div>
          </form>
        </div>


      </div>
    </div>
    <div class="footer">
      <label>Copyright @ 2019</label>
    </div>
    </body>
    </html>
