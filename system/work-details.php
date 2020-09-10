<?php include '../inc/header.php';?>
<?php
if (isset($_GET["target"]) && is_numeric($_GET["target"]) && $_GET["target"] > 0) {
    $job_id = test_input($_GET["target"]);
    $user_id = $_SESSION['user_id'];
    $std_two = false;
?>
      <div class="section-four content-width">
        <div class="card-sec">
          <?php
          $std = false;
          $task_sql = "SELECT total_count,details,proves,rate,
              (SELECT COUNT(*) FROM task_table WHERE status = 'accept' AND job_id = '$job_id'
            ) AS Total_accept,
              (SELECT COUNT(*) FROM task_table WHERE status = 'reject' AND job_id = '$job_id'
            ) AS Total_reject,
              (SELECT COUNT(*) FROM task_table WHERE worker_id = '$user_id' AND job_id = '$job_id'
            ) AS check_exits
          FROM job_table WHERE id = '$job_id'";
          $task_read = $db->select($task_sql);
          if ($task_read) {
              $count_job = mysqli_num_rows($task_read);
              if($count_job > 0){
                  $task_row = $task_read->fetch_assoc();
                  $exits =  $task_row['check_exits'];
                  if($exits > 0 ){
                    $std = true;
                  }else{
                    $total_count = $task_row['total_count'];
                      $total_count = $task_row['total_count'];
                      $task_details = $task_row['details'];
                      $task_proves = $task_row['proves'];
                      $task_rate = $task_row['rate'];
                      $accept = $task_row['Total_accept'];
                      $reject = $task_row['Total_reject'];
                      $check = $accept + $reject;
                      if($accept == 0 || $check == 0){
                        $per_suc = 0;
                      }else{
                        $per_suc = $accept/$check;
                        $per_suc = number_format( $per_suc * 100, 0 );
                      }
                  }

              }else{
                $std = true;
              }
            }

            ?>

            <?php if ($std){ ?>
              <?php if (isset($_GET['insert-status']) && $_GET['insert-status'] == 'success'){
                $std_two = true; ?>
                <div style="margin:30px auto;padding:20px;background-color:#fff;color:#20DF2E" class="section-five">
                  <div class="login-head">
                    <h6>Submission succefull. </h6>
                  </div>
                </div>
              <?php } ?>
              <?php if (isset($_GET['insert-status']) && $_GET['insert-status'] == 'img-error'){
                $std_two = true; ?>
                <div style="margin:30px auto;padding:20px;background-color:#fff;color:red" class="section-five">
                    <h6>System can't insert file. please read the instraction and try again! </h6>
                </div>
              <?php } ?>
              <?php if (isset($_GET['insert-status']) && $_GET['insert-status'] == 'error'){
                $std_two = true; ?>
                <div style="margin:30px auto;padding:20px;background-color:#fff;color:red" class="section-five">
                    <h6>Failed to submit your work. please try again </h6>
                </div>
              <?php } ?>
              <?php if (isset($_GET['insert-status']) && $_GET['insert-status'] == 'empty'){
                $std_two = true; ?>
                <div style="margin:30px auto;padding:20px;background-color:#fff;color:red" class="section-five">
                    <h6>Fill the input field !</h6>
                </div>
              <?php } ?>
              <?php if(!$std_two){ ?>
                <div style="margin:30px auto;padding:20px;background-color:#fff;color:black" class="section-five">
                    <h6>There is no work currently available in this task OR you done this work before!</h6>
                </div>
                <div style="text-align:center;" class="">
                  <h5><a href="../index.php"><< Back</a></h5>
                </div>

              <?php } ?>

            <?php }else{ ?>
              <div class="card-header-sec">
                <label>facebook like</label>
              </div>
              <div class="card-body-sec">
                <label>Work done: <span class="badge badge-pill badge-light"><?php echo $check;?>/<?php echo $total_count;?></span></label><br>
                <label>Success rate: <span class="badge badge-pill badge-light"><?php echo $per_suc;?>%</span></label><br>
                <label>Work rate: <span class="badge badge-pill badge-light"><?php echo $task_rate;?> tk</span></label><br>
                <label>Task count: <span class="badge badge-pill badge-light">1 time</span></label><br>
              </div>
            </div>
            <div class="card-sec">
              <div class="card-top-sec">
                <label>What you need to do-</label>
              </div>
              <div class="card-2-body-sec">
                <p><?php echo nl2br($task_details);?>
                </p>
              </div>
            </div>
            <div class="card-sec">
              <div class="card-top-sec">
                <label>Required proves-</label>
              </div>
              <div class="card-2-body-sec">
                <p>
                  <?php echo nl2br($task_proves);?>
                </p>
              </div>
            </div>
            <div class="action-btn-cont">
              <button id="accept_btn" type="button" class="btn btn-info btn-sm float-left" name="button">I accept this job</button>
            </div>
            <form class="" action="../func/up-func.php" method="post" enctype="multipart/form-data">
              <div id="accept_job" class="card-sec">
                <div class="card-top-sec">
                  <label>Give your proves-</label>
                </div>
                <input hidden type="number"  name="job_id" value="<?php echo $job_id?>">
                <div class="card-2-body-sec">
                  <textarea required id="accept_input" name="work_details" placeholder="Enter text here..." rows="4"></textarea>
                </div>
              <div style="margin-top:10px;" class="card-2-body-sec">
                <div class="form-group">
                  <label for="exampleFormControlFile1">Upload proof file if required.(Optional)</label>
                  <input name="ss_file[]" type="file" class="form-control-file">
                </div>
              </div>
              <div class="action-btn-cont">
                <button type="submit" name="work_submit" class="btn btn-dark btn-sm">Submit</button>
              </div>

              </div>

            </form>
            <?php } ?>

      </div>
      <script src="../assets/js/main.js"></script>
    <?php }else{
      header('location:../index.php');
    }?>

<?php include '../inc/footer.php';?>
