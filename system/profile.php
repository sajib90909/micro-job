<?php include '../inc/header.php';?>
<?php

$limit = 5;
$start_from = 0;
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) {
    $pn = test_input($_GET["page"]);
} else {
    $pn = 1;
}
$start_from = ($pn - 1) * $limit;


if(isset($_SESSION['login']) && isset($_SESSION['user_id']) && $_SESSION['login'] == true){
  $user_id = $_SESSION['user_id'];
  $worker_query = "SELECT * FROM workers_table WHERE user_id = '$user_id'";
  $worker_read = $db->select($worker_query);
  if ($worker_read) {
      $count_job = mysqli_num_rows($worker_read);
      if($count_job > 0){
        $worker_row = $worker_read->fetch_assoc();
        $work_title = $worker_row['name'];
        $user_balance = $worker_row['balance'];
        $user_phone = $worker_row['phone'];
        $user_email = $worker_row['email'];
        $user_occupation = $worker_row['occupation'];
        $user_company = $worker_row['company'];
        $user_address = $worker_row['address'];
        $user_zip_code = $worker_row['zip_code'];
        $user_status = $worker_row['status'];
        $user_gender = $worker_row['gender'];
        $user_birthdate = $worker_row['birthdate'];
        $user_city = $worker_row['city'];
        $user_widthdraw = $worker_row['withdraw'];
        $user_total_earn = $worker_row['total_earn'];
        $user_id_n = $worker_row['user_id'];
        $user_img = $worker_row['img_url'];
        $user_nid = $worker_row['nid'];
        if($worker_row['level'] == 2){
          $user_level = 'two';
        }elseif($worker_row['level'] == 3){
          $user_level = 'three';
        }else{
          $user_level = 'one';
        }

  ?>
      <div class="section-four content-width">


        <?php include '../inc/profile-menu.php';?>

        <?php if (isset($_GET['request']) && $_GET['request'] == 'success'){ ?>
          <div style="border: 1px solid black; color:green;" class="card-header-sec">
              <label>Your request is submit !</label>
          </div>
        <?php } ?>
        <?php if (isset($_GET['failed'])){ ?>
          <div style="border: 1px solid red; color:red;" class="card-header-sec">
            <label><?php echo $_GET['failed']; ?></label>
          </div>
        <?php } ?>
        <div class="card-header-sec">
          <label>Account Information</label>
        </div>
        <div class="profile-area row">
          <div class="col-sm-6">
            <div class="card-body-sec-p card-body-mg-r">
              <label>Name: <span class="badge badge-pill badge-light"><?php echo $work_title; ?></span></label><br>
              <label>Phone: <span class="badge badge-pill badge-light"><?php echo $user_phone; ?></span></label><br>
              <label>Email: <span class="badge badge-pill badge-light"><?php echo $user_email; ?></span>
                <?php if($user_status == 'unverified'){ ?>
                  <span style="font-size:9px;" class="badge badge-pill badge-warning">unverify</span>
                <?php }else{ ?>
                  <span style="font-size:9px;" class="badge badge-pill badge-success">verify</span>
                <?php }?></label><br>
              <label>Gender: <span class="badge badge-pill badge-light"><?php echo $user_gender; ?></span></label><br>
              <label>Birthdate: <span class="badge badge-pill badge-light"><?php echo $user_birthdate; ?></span></label><br>
              <label>Occupation: <span class="badge badge-pill badge-light"><?php echo $user_occupation; ?></span></label><br>
              <label>Institute/Company: <span class="badge badge-pill badge-light"><?php echo $user_company; ?> university</span></label><br>
              <label>Address: <span class="badge badge-pill badge-light"><?php echo $user_address; ?></span></label><br>
              <label>Zip code: <span class="badge badge-pill badge-light"><?php echo $user_zip_code; ?></span></label><br>
              <label>City: <span class="badge badge-pill badge-light"><?php echo $user_city; ?></span></label><br>
              <label>Nid: <span class="badge badge-pill badge-light"><?php echo $user_nid; ?></span></label><br>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="card-body-sec-p card-body-mg-l">
              <div class="img-sec">
                <img src="../user_img/<?php echo $user_img;?>" alt="">
              </div>
              <label>User ID: <span class="badge badge-pill badge-light"><?php echo $user_id_n; ?></span></label><br>
              <label>Lavel: <span class="badge badge-pill badge-light"><?php echo $user_level; ?></span></label><br>
              <label>Balance: <span class="badge badge-pill badge-light"><?php echo $user_balance; ?> tk</span></label><br>
              <label>Withdraw: <span class="badge badge-pill badge-light"><?php echo $user_widthdraw; ?> tk</span></label><br>
              <label>Total Earn: <span class="badge badge-pill badge-light"><?php echo $user_total_earn; ?> tk</span></label><br>
              <div class="withdraw-btn">
                <label><a href="" class="action-btn btn-light" name="button" data-toggle="modal" data-target="#withdrawmodel">Withdraw</a></label>
              </div>
            </div>
          </div>

        </div>


      </div>
      <div style="padding-top:5px;" class="section-four content-width">
        <div class="card-header-sec">
          <label>Payment History</label>
        </div>
        <div class="content-table">
          <table class="table table-bordered table-sm">
            <thead class="">
              <tr>
                <th scope="col" class="sl">SL</th>
                <th scope="col" class="item">Date</th>
                <th scope="col" class="item">Method</th>
                <th scope="col" class="item">Amount</th>
                <th scope="col" class="item">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 0;
              $i = $start_from;
              $worker_query = "SELECT * FROM payment_table WHERE user_id = '$user_id' LIMIT $start_from,$limit ";
              $worker_read = $db->select($worker_query);
              if ($worker_read) {
                  $count_job = mysqli_num_rows($worker_read);
                  if($count_job > 0){
                    while ($worker_row = $worker_read->fetch_assoc()) {
                      $i++;

                       ?>
                      <tr>
                        <th><?php echo $i; ?></th>
                        <td><?php echo $worker_row['req_date']; ?></td>
                        <td><?php echo $worker_row['method']; ?></td>
                        <td><?php echo $worker_row['withdraw_req']; ?> tk</td>
                        <?php if($worker_row['status'] == 'complete'){
                          echo '<td style="color:green;">Complete</td>';
                        }elseif($worker_row['status'] == 'reject'){
                          echo '<td style="color:red;">Reject</td>';
                        }else{
                          echo '<td style="color:#FF9A18;">Proccessing</td>';
                        }?>
                      </tr>
                    <?php }

                  }else{
                    echo 'no data found!';
                  }
                }

              ?>

            </tbody>
          </table>
          <div class="pagination-section">
            <div class="pagination-sec">
              <ul class="pagination pagination-content">
                <?php
                $job_query = "SELECT * FROM payment_table WHERE user_id = '$user_id'";
                $job_read = $db->select($job_query);
                if ($job_read) {
                  $count_job = mysqli_num_rows($job_read);
                  $count4 = $count_job;
                  $total_pages = ceil($count4 / $limit);
                  $k = (($pn+1>$total_pages)?$total_pages-1:(($pn-1<1)?2:$pn));
                  $pagLink = "";
                  if($total_pages > 1){
                  if($pn>=2){
                      echo "<a href='?page=".($pn-1)."'><li class='page-btn'><</li></a>";
                      echo "<li><a href='?page=1'> 1 </a></li>";
                      if(($pn-1) > 2){
                        echo "<li><a href='?page=".($pn-2)."'> ... </a></li>";
                      }
                  }
                  if($pn == 1){
                    echo "<li class='active'><a href='?page=1'> 1 </a></li>";
                  }
                  for ($i=-1; $i<=1; $i++) {
                      if($k+$i != 1 && $k+$i != $total_pages && $k+$i < $total_pages && $k+$i > 0){
                        if($k+$i==$pn)
                          $pagLink .= "<li class='active'><a href='?page=".($k+$i)."'>".($k+$i)."</a></li>";
                        else
                          $pagLink .= "<li><a href='?page=".($k+$i)."'>".($k+$i)."</a></li>";
                      }
                  };
                  echo $pagLink;
                  if($pn == $total_pages){
                    echo "<li class='active'><a href='?page=".$total_pages."'> ".$total_pages." </a></li>";
                  }
                  if($pn<$total_pages){
                      if(($total_pages-$pn) > 2){
                        echo "<li><a href='?page=".($pn+2)."'> ... </a></li>";
                      }
                      echo "<li><a href='?page=".$total_pages."'> ".$total_pages." </a></li>";
                      echo "<a href='?page=".($pn+1)."'><li class='page-btn'> > </li></a>";

                  }

                }
              }
                ?>
              </ul>
             </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="withdrawmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Withdraw</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="edit-info">
                <form class="form-horizontal" action="../func/payment_func.php" method="post">
                  <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 lebel-class">Amount</label>
                    <div class="col-sm-9">
                      <input type="number" class="input-class" id="inputPassword" name="amount_req" autocomplete="off" placeholder="Enter Amount">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 lebel-class">Your Balance</label>
                    <div class="col-sm-9">
                      <label class="input-class"><?php echo $user_balance;?> tk.</label>
                    </div>
                  </div>
                  <fieldset class="form-group redio-area">
                    <div class="row">
                      <legend class="lebel-class col-sm-3 pt-0">Method</legend>
                      <div class="col-sm-9">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="method" id="gridRadios1" value="bkash" checked>
                          <label class="form-check-label" for="gridRadios1">
                            Bkash
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="method" id="gridRadios2" value="rocket">
                          <label class="form-check-label" for="gridRadios2">
                            Rocket
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="method" id="gridRadios1" value="top_up">
                          <label class="form-check-label" for="gridRadios1">
                            Top up
                          </label>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 lebel-class">Account Number</label>
                    <div class="col-sm-9">
                      <input type="number" class="input-class" id="inputPassword" name="account_no" autocomplete="off" placeholder="Enter Your Acount number">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 lebel-class">password</label>
                    <div class="col-sm-9">
                      <input type="password" class="input-class" name="password" autocomplete="off" id="inputPassword" placeholder="Enter password">
                    </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            <button type="submit" name="payment_req" class="btn btn-primary btn-sm">Confirm</button>
            </form>
          </div>
        </div>
      </div>
      </div>
      <?php
          }
        }
      }
      ?>
<?php include '../inc/footer.php';?>
