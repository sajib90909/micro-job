<?php include '../include/header.php';?>
<?php include '../include/sidemenu.php';?>
<?php
include '../database/config.php';
include '../database/database.php';
$db = new database();
function test_input($data) { //filter value function
    $db = new database();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = strtolower($data);
    $data = mysqli_real_escape_string($db->link, $data);
    return $data;
}
$status = 'uncheck';
if (isset($_GET["status"]) && ($_GET["status"] == 'check' || $_GET["status"] == 'uncheck')) {
    $status = test_input($_GET["status"]);
}
if (isset($_GET["target"]) && is_numeric($_GET["target"]) && $_GET["target"] > 0) {
    $job_id = test_input($_GET["target"]);
} else {
    $job_id = 0;
}
$limit = 10;
$start_from = 0;
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) {
    $pn = test_input($_GET["page"]);
} else {
    $pn = 1;
}
$start_from = ($pn - 1) * $limit;
?>
        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <!-- <span>Toggle Sidebar</span> -->
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">

                              <form class="form-inline md-form form-sm active-pink active-pink-2 mt-2" style="">
                                <i class="fas fa-search" aria-hidden="true"></i>
                                <input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="Search"
                                  aria-label="Search">
                              </form>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="row">
              <div class="col-md-8">
                <form class="" action="../functions/task_update.php" method="post">
                <?php
                $i = 0;
                $i = $start_from;
                $task_count_sql = "SELECT(
                      SELECT COUNT(*) FROM task_table WHERE status = 'uncheck' AND job_id = '$job_id'
                    ) AS Total_uncheck,
                    (SELECT COUNT(*) FROM task_table WHERE status = 'accept' AND job_id = '$job_id'
                  ) AS Total_accept,
                  (SELECT COUNT(*) FROM task_table WHERE status = 'reject' AND job_id = '$job_id'
                ) AS Total_reject
                FROM task_table";
                $reject_task_read = $db->select($task_count_sql);
                if($reject_task_read){
                  $task_row = $reject_task_read->fetch_assoc();
                  $uncheck = $task_row['Total_uncheck'];
                  $accept =  $task_row['Total_accept'];
                  $reject =  $task_row['Total_reject'];
                  $check = $accept + $reject;
                  $submit = $check + $uncheck;
                }
                $job_query = "SELECT * FROM job_table WHERE id = '$job_id'";
                $job_read = $db->select($job_query);
                if ($job_read) {
                    $count_job = mysqli_num_rows($job_read);
                    if($count_job > 0){
                      $job_row = $job_read->fetch_assoc();
                      $work_title = $job_row['job_name'];
                      $rate = $job_row['rate'];
                      $total_count = $job_row['total_count'];
                      $end_date = $job_row['end_date'];
                      $assign_date = $job_row['assign_date'];
                      $details = $job_row['details'];
                      $proves = $job_row['proves'];
                    }
                  }
                if ($status == 'check') {
                  $task_query = "SELECT * FROM task_table WHERE (status = 'accept' OR status = 'reject') AND job_id = '$job_id' LIMIT $start_from,$limit";
                }else{
                  $task_query = "SELECT * FROM task_table WHERE status = '$status' AND job_id = '$job_id' LIMIT $start_from,$limit";
                }
                $task_read = $db->select($task_query);
                if ($task_read) {
                    $count_task = mysqli_num_rows($task_read);
                    if($count_task > 0){
                      while ($task_row = $task_read->fetch_assoc()) {

                        $workers_id = $task_row['worker_id'];
                        $worker_name_sql = "SELECT name FROM workers_table WHERE user_id = '$workers_id'";
                        $worker_name_read = $db->select($worker_name_sql);
                        if($worker_name_read){
                          $worker_name_row = $worker_name_read->fetch_assoc();
                          $worker_name = $worker_name_row['name'];
                          $task_id = $task_row['id'];
                          $dev_cmmnt = $task_row['dev_comment'];
                          $work_proves = $task_row['work_details'];

                          $i++;

                  ?>
                <div class="card uncheck-item">
                  <div class="card-header">
                    <?php echo $i.'. '.$worker_name;?>
                  </div>
                  <div class="card-body row">
                    <div class="work-info col-7">
                      <?php echo nl2br($work_proves);?>
                    </div>
                    <div class="col-5">
                      <div class="">

                          <input type="number" hidden name="task_id[]" value="<?php echo $task_id?>">
                          <input type="number" hidden name="target" value="<?php echo $job_id?>">
                          <input type="text" hidden name="status_page" value="<?php echo $status?>">
                          <input type="number" hidden name="task_rate" value="<?php echo $rate?>">
                          <input type="text" hidden name="worker_id_<?php echo $task_id;?>" value="<?php echo $workers_id?>">
                          <textarea class="comment" name="dev_cmnt_<?php echo $task_id;?>" rows="4" placeholder="developer comment............"><?php echo nl2br($dev_cmmnt);?></textarea>
                          <div class="text-right">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" <?php if($task_row['status'] == 'accept'){echo 'checked';}?> name="status_<?php echo $task_id;?>" id="inlineRadio1" value="accept">
                              <label style="color:green" class="form-check-label" for="inlineRadio1">Accept</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" <?php if($task_row['status'] == 'reject'){echo 'checked';}?> name="status_<?php echo $task_id;?>" id="inlineRadio2" value="reject">
                              <label style="color:#F0470C" class="form-check-label" for="inlineRadio2">Reject</label>
                            </div>
                          </div>

                      </div>

                    </div>

                  </div>
                </div>
                <?php
              }
            }?>
            <div class="card uncheck-item">
              <div class="card-header">
                <button style="width:100%;" type="submit" class="btn btn-secondary" name="button"><?php if($status == 'check'){ echo 'Update';}else{ echo 'Save';}?></button>
              </div>
            </div>
          <?php
          }
        }
                ?>
                <div class="pagination-section">
                  <div class="pagination-sec">
                    <ul class="pagination pagination-content">
                      <?php


                      if ($status == 'check') {
                        $task_query = "SELECT * FROM task_table WHERE (status = 'accept' OR status = 'reject') AND job_id = '$job_id' ";
                      }else{
                        $task_query = "SELECT * FROM task_table WHERE status = '$status' AND job_id = '$job_id' ";
                      }
                      $task_read = $db->select($task_query);
                      if ($task_read) {
                        $count_task = mysqli_num_rows($task_read);
                        $count4 = $count_task;
                        $email_listing = "index.php";
                        $total_pages = ceil($count4 / $limit);
                        $k = (($pn+1>$total_pages)?$total_pages-1:(($pn-1<1)?2:$pn));
                        $pagLink = "";
                        if($total_pages > 1){
                        if($pn>=2){
                            echo "<a href='?target=".$job_id."&&status=".$status."&&page=".($pn-1)."'><li class='page-btn'><</li></a>";
                            echo "<li><a href='?target=".$job_id."&&status=".$status."&&page=1'> 1 </a></li>";
                            if(($pn-1) > 2){
                              echo "<li><a href='?target=".$job_id."&&status=".$status."&&page=".($pn-2)."'> ... </a></li>";
                            }
                        }
                        if($pn == 1){
                          echo "<li class='active'><a href='?target=".$job_id."&&status=".$status."&&page=1'> 1 </a></li>";
                        }
                        for ($i=-1; $i<=1; $i++) {
                            if($k+$i != 1 && $k+$i != $total_pages && $k+$i < $total_pages && $k+$i > 0){
                              if($k+$i==$pn)
                                $pagLink .= "<li class='active'><a href='?target=".$job_id."&&status=".$status."&&page=".($k+$i)."'>".($k+$i)."</a></li>";
                              else
                                $pagLink .= "<li><a href='?target=".$job_id."&&status=".$status."&&page=".($k+$i)."'>".($k+$i)."</a></li>";
                            }
                        };
                        echo $pagLink;
                        if($pn == $total_pages){
                          echo "<li class='active'><a href='?target=".$job_id."&&status=".$status."&&page=".$total_pages."'> ".$total_pages." </a></li>";
                        }
                        if($pn<$total_pages){
                            if(($total_pages-$pn) > 2){
                              echo "<li><a href='?target=".$job_id."&&status=".$status."&&page=".($pn+2)."'> ... </a></li>";
                            }
                            echo "<li><a href='?target=".$job_id."&&status=".$status."&&page=".$total_pages."'> ".$total_pages." </a></li>";
                            echo "<a href='?target=".$job_id."&&status=".$status."&&page=".($pn+1)."'><li class='page-btn'> > </li></a>";

                        }

                      }
                    }
                      ?>
                   </div>
                </div>
                </form>
              </div>
              <div class="work-details col-md-4">
                <div class="card">
                  <div class="card-header">
                    <?php echo $work_title;?>
                  </div>
                  <div class="card-body">
                    <div class="work-info">
                      <p>Work Done: <?php echo $accept.'/'.$total_count?></p>
                      <p>Reject: <?php echo $reject?></p>
                      <p>Total Submit: <?php echo $submit?></p>
                      <p>Work rate: <?php echo $rate?> tk.</p>
                    </div>
                    <p>Waht need to do?</p>
                    <div class="work-todo">
                      <p><?php echo nl2br($details)?>
                      </p>
                    </div>
                    <p>Required proof</p>
                    <div class="work-proof">
                      <p>
                        <?php echo nl2br($proves)?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>

            </div>
        </div>
<?php include '../include/footer.php';?>
