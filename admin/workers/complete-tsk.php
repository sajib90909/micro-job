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
$workers_id = 'null';
if (isset($_GET["target"])) {
    $workers_id = test_input($_GET["target"]);
}
$limit = 5;
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
                <?php
                $i = 0;
                $i = $start_from;
                $task_count_sql = "SELECT(
                      SELECT COUNT(*) FROM task_table WHERE status = 'uncheck' AND worker_id = '$workers_id'
                    ) AS Total_uncheck,
                    (SELECT COUNT(*) FROM task_table WHERE status = 'accept' AND worker_id = '$workers_id'
                  ) AS Total_accept,
                  (SELECT COUNT(*) FROM task_table WHERE status = 'reject' AND worker_id = '$workers_id'
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
                  if($accept == 0 || $check == 0){
                    $per_suc = 0;
                  }else{
                    $per_suc = $accept/$check;
                    $per_suc = number_format( $per_suc * 100, 0 );
                  }
                }
                $worker_query = "SELECT * FROM workers_table WHERE user_id = '$workers_id'";
                $worker_read = $db->select($worker_query);
                if ($worker_read) {
                    $count_job = mysqli_num_rows($worker_read);
                    if($count_job > 0){
                      $worker_row = $worker_read->fetch_assoc();
                      $work_title = $worker_row['name'];
                    }
                  }

                $task_query = "SELECT * FROM task_table WHERE worker_id = '$workers_id' LIMIT $start_from,$limit";
                $task_read = $db->select($task_query);
                if ($task_read) {
                    $count_task = mysqli_num_rows($task_read);
                    if($count_task > 0){
                      while ($task_row = $task_read->fetch_assoc()) {
                        $job_id = $task_row['job_id'];
                        $job_name_sql = "SELECT job_name FROM job_table WHERE id = '$job_id'";
                        $job_name_read = $db->select($job_name_sql);
                        if($job_name_read){
                          $job_name_row = $job_name_read->fetch_assoc();
                          $job_name = $job_name_row['job_name'];
                          $i++;


                  ?>
                <div class="card uncheck-item">
                  <div class="card-header">
                    <?php echo $i.'. '.$job_name; ?>
                  </div>
                  <div class="card-body row">
                    <div class="work-info col-7">
                      <?php echo nl2br($task_row['work_details'])?>
                    </div>
                    <div class="col-5">
                      <div class="">
                        <div class="comment">
                          <?php
                          if(!empty($task_row['dev_comment'])){
                            echo nl2br($task_row['dev_comment']);
                          }else{
                            echo "No comments";
                          }

                           ?>
                        </div>
                          <?php if($task_row['status'] == 'accept'){?>
                          <div class="alert text-center alert-success" role="alert">
                              Accept
                          </div>
                        <?php }elseif($task_row['status'] == 'reject'){?>
                          <div class="alert text-center alert-danger" role="alert">
                              reject
                          </div>
                        <?php }else{?>
                          <div class="alert text-center alert-warning" role="alert">
                              Processing
                          </div>
                        <?php }?>
                      </div>

                    </div>

                  </div>
                </div>
                <?php
              }
            }
          }
        }
                ?>


                <div class="pagination-section">
                  <div class="pagination-sec">
                    <ul class="pagination pagination-content">
                      <?php
                      $task_query = "SELECT * FROM task_table WHERE worker_id = '$workers_id' ";
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
                            echo "<a href='?target=".$workers_id."&&page=".($pn-1)."'><li class='page-btn'><</li></a>";
                            echo "<li><a href='?target=".$workers_id."&&page=1'> 1 </a></li>";
                            if(($pn-1) > 2){
                              echo "<li><a href='?target=".$workers_id."&&page=".($pn-2)."'> ... </a></li>";
                            }
                        }
                        if($pn == 1){
                          echo "<li class='active'><a href='?target=".$workers_id."&&page=1'> 1 </a></li>";
                        }
                        for ($i=-1; $i<=1; $i++) {
                            if($k+$i != 1 && $k+$i != $total_pages && $k+$i < $total_pages && $k+$i > 0){
                              if($k+$i==$pn)
                                $pagLink .= "<li class='active'><a href='?target=".$workers_id."&&page=".($k+$i)."'>".($k+$i)."</a></li>";
                              else
                                $pagLink .= "<li><a href='?target=".$workers_id."&&page=".($k+$i)."'>".($k+$i)."</a></li>";
                            }
                        };
                        echo $pagLink;
                        if($pn == $total_pages){
                          echo "<li class='active'><a href='?target=".$workers_id."&&page=".$total_pages."'> ".$total_pages." </a></li>";
                        }
                        if($pn<$total_pages){
                            if(($total_pages-$pn) > 2){
                              echo "<li><a href='?target=".$workers_id."&&page=".($pn+2)."'> ... </a></li>";
                            }
                            echo "<li><a href='?target=".$workers_id."&&page=".$total_pages."'> ".$total_pages." </a></li>";
                            echo "<a href='?target=".$workers_id."&&page=".($pn+1)."'><li class='page-btn'> > </li></a>";

                        }

                      }
                    }
                      ?>
                    </ul>
                   </div>
                </div>
              </div>
              <div class="work-details col-md-4">
                <div class="card">
                  <div class="card-header">
                    <?php echo $work_title;?>
                  </div>
                  <div class="card-body">
                    <div class="work-info">
                      <p>Task Submit: <span class="badge badge-secondary"><?php echo $submit;?></span> </p>
                      <p>Success: <span class="badge badge-info"><?php echo $accept;?></span></p>
                      <p>Reject: <span class="badge badge-warning"><?php echo $reject;?></span></p>
                      <p>Success Rate: <span class="badge badge-success"><?php echo $per_suc;?>%</span></p>
                    </div>
                  </div>
                </div>
              </div>

            </div>
        </div>
<?php include '../include/footer.php';?>
