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
$action_status = 'active';
if (isset($_GET["action-status"]) && ($_GET["action-status"] == 'unverified' || $_GET["action-status"] == 'banned')) {
    $action_status = test_input($_GET["action-status"]);
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
            <div class="table-overflow">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Name</th>
                    <th scope="col">User ID</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Balance</th>
                    <th scope="col">User IP</th>
                    <th scope="col">task Complete</th>
                    <th scope="col">Success rate</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // $user_query = "SELECT * FROM job_table WHERE validation  = 'Valid' LIMIT $start_from,$limit";
                  $i = 0;
                  $i = $start_from;
                  $job_query = "SELECT * FROM workers_table WHERE status = '$action_status' LIMIT $start_from,$limit";
                  $job_read = $db->select($job_query);
                  if ($job_read) {
                      $count_job = mysqli_num_rows($job_read);
                      if($count_job > 0){
                        while ($job_row = $job_read->fetch_assoc()) {
                          $job_id = $job_row['user_id'];
                          $task_count_sql = "SELECT(
                                SELECT COUNT(*) FROM task_table WHERE status = 'uncheck' AND worker_id = '$job_id'
                              ) AS Total_uncheck,
                              (SELECT COUNT(*) FROM task_table WHERE status = 'accept' AND worker_id = '$job_id'
                            ) AS Total_accept,
                            (SELECT COUNT(*) FROM task_table WHERE status = 'reject' AND worker_id = '$job_id'
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
                            $i++;

                    ?>
                  <tr>
                    <th scope="row"><?php echo $i;?></th>
                    <td class="job-name" style="cursor:pointer;" data-toggle="modal" data-target="#<?php echo $job_row['user_id'];?>"><?php echo $job_row['name'];?></td>
                    <td style="cursor:pointer;" data-toggle="modal" data-target="#<?php echo $job_row['user_id'];?>"><?php echo $job_row['user_id'];?></td>
                    <td style="cursor:pointer;" data-toggle="modal" data-target="#<?php echo $job_row['user_id'];?>"><?php echo $job_row['phone'];?></td>
                    <td style="cursor:pointer;" data-toggle="modal" data-target="#<?php echo $job_row['user_id'];?>"><?php echo $job_row['email'];?></td>
                    <td style="cursor:pointer;" data-toggle="modal" data-target="#<?php echo $job_row['user_id'];?>"><?php echo $job_row['balance'];?></td>
                    <td style="cursor:pointer;" data-toggle="modal" data-target="#<?php echo $job_row['user_id'];?>"><?php echo $job_row['user_ip'];?></td>
                    <td><a href="complete-tsk.php?target=<?php echo $job_row['user_id'];?>" class="action-btn btn btn-secondary btn-sm"><?php echo $submit;?></a></td>
                    <td style="cursor:pointer;color:green" data-toggle="modal" data-target="#<?php echo $job_row['user_id'];?>"><?php echo $per_suc;?>%</td>
                    <td style="cursor:pointer;" data-toggle="modal" data-target="#<?php echo $job_row['user_id'];?>">
                      <?php
                      if($job_row['status'] == 'unverified'){ ?>
                        <span class="badge badge-warning">Unverified</span>
                      <?php }elseif($job_row['status'] == 'banned'){ ?>
                        <span class="badge badge-dark">Banned</span>
                      <?php }else{ ?>
                        <span class="badge badge-success">Active</span>
                    <?php  } ?>

                    </td>
                  </tr>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- Modal -->
<div class="modal fade" id="<?php echo $job_row['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Worker Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="card">
            <div class="card-body row">
              <div class="col-md-6">
                <div class="row">
                  <label for="inputPassword" class="col-6 lebel-class">Balance</label>
                  <div class="col-6">
                    <label for="inputPassword" class="lebel-class">: <span class="badge badge-primary"><?php echo $job_row['balance'];?> tk</span></label>
                  </div>
                </div>
                <div class="row">
                  <label for="inputPassword" class="col-6 lebel-class">Withdraw</label>
                  <div class="col-6">
                    <label for="inputPassword" class="lebel-class">: <span class="badge badge-light"><?php echo $job_row['withdraw'];?> tk</span></label>
                  </div>
                </div>
                <div class="row">
                  <label for="inputPassword" class="col-6 lebel-class">Total Earn</label>
                  <div class="col-6">
                    <label for="inputPassword" class="lebel-class">: <span class="badge badge-success"><?php echo $job_row['total_earn'];?> tk</span></label>
                  </div>
                </div>
                <div class="row">
                  <label for="inputPassword" class="col-6 lebel-class">Last withdraw</label>
                  <div class="col-6">
                    <label for="inputPassword" class="lebel-class">: <span class="badge badge-secondary"><?php echo $job_row['last_withdraw'];?> tk</span></label>
                    <label for="inputPassword" class="lebel-class">(<?php echo $job_row['last_withdraw_date'];?>)</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <label for="inputPassword" class="col-6 lebel-class">Task Complete</label>
                  <div class="col-6">
                    <label for="inputPassword" class="lebel-class">: <span class="badge badge-secondary"><?php echo $submit;?></span></label>
                  </div>
                </div>
                <div class="row">
                  <label for="inputPassword" class="col-6 lebel-class">Success</label>
                  <div class="col-6">
                    <label for="inputPassword" class="lebel-class">: <span class="badge badge-info"><?php echo $accept;?></span></label>
                  </div>
                </div>
                <div class="row">
                  <label for="inputPassword" class="col-6 lebel-class">Reject</label>
                  <div class="col-6">
                    <label for="inputPassword" class="lebel-class">: <span class="badge badge-warning"><?php echo $reject;?></span></label>
                  </div>
                </div>
                <div class="row">
                  <label for="inputPassword" class="col-6 lebel-class">Success Rate</label>
                  <div class="col-6">
                    <label for="inputPassword" class="lebel-class">: <span class="badge badge-success"><?php echo $per_suc;?>%</span></label>
                  </div>
                </div>
              </div>
            </div>
          </div><br>
          <div class="card">
            <div class="card-body">
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">Name</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['name'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">Phone</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['phone'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">Email</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['email'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">Occupation</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['occupation'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">Company/Institute</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['company'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">address</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['address'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">Zip Code</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['zip_code'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">City</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['city'];?></label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-4 lebel-class">Registration Date</label>
                  <div class="col-sm-8">
                    <label for="inputPassword" class="lebel-class">: <?php echo $job_row['reg_date'];?></label>
                  </div>
                </div>
                <?php
                if($job_row['status'] == 'unverified'){ ?>
                  <span class="badge badge-warning">Email Unverified</span>
                <?php }else{ ?>
                  <span class="badge badge-success">Email verified</span>
                <?php } ?>
                <?php
                if($job_row['status'] == 'banned'){ ?>
                  <span class="badge badge-dark">Banned</span>
                <?php }elseif($job_row['status'] == 'active'){ ?>
                  <span class="badge badge-primary">Active</span>
                <?php } ?>


            </div>
          </div>
      </div>
      <div class="modal-footer">
        <?php
        if($job_row['status'] == 'unverified'){ ?>
        <?php }else{ ?>
          <form class="form-horizontal" action="../functions/worker_status.php" method="post">
            <input type="text" name="user_id" hidden value="<?php echo $job_row['user_id'];?>">
            <input type="text" name="status" hidden value="<?php echo $job_row['status'];?>">
            <input type="text" name="action_status" hidden value="<?php echo $action_status;?>">
            <input type="text" name="page" hidden value="<?php echo $pn;?>">
            <?php if($job_row['status'] == 'active'){?>
              <button type="submit" name="worker_ban" class="btn btn-dark btn-sm">Ban this worker</button>
            <?php }else{?>
              <button type="submit" name="worker_ban" class="btn btn-success btn-sm">Active this worker</button>
            <?php }?>
          </form>
        <?php } ?>
        <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Close</button>
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
<!-- ////////////////////////////////////////////////////////////////////// -->



                </tbody>
              </table>
              <div class="pagination-section">
                <div class="pagination-sec">
                  <ul class="pagination pagination-content">
                    <?php


                    $job_query = "SELECT * FROM workers_table WHERE status = '$action_status'";
                    $job_read = $db->select($job_query);
                    if ($job_read) {
                      $count_job = mysqli_num_rows($job_read);
                      $count4 = $count_job;
                      $email_listing = "index.php";
                      $total_pages = ceil($count4 / $limit);
                      $k = (($pn+1>$total_pages)?$total_pages-1:(($pn-1<1)?2:$pn));
                      $pagLink = "";
                      if($total_pages > 1){
                      if($pn>=2){
                          echo "<a href='index.php?page=".($pn-1)."'><li class='page-btn'><</li></a>";
                          echo "<li><a href='index.php?page=1'> 1 </a></li>";
                          if(($pn-1) > 2){
                            echo "<li><a href='index.php?page=".($pn-2)."'> ... </a></li>";
                          }
                      }
                      if($pn == 1){
                        echo "<li class='active'><a href='index.php?page=1'> 1 </a></li>";
                      }
                      for ($i=-1; $i<=1; $i++) {
                          if($k+$i != 1 && $k+$i != $total_pages && $k+$i < $total_pages && $k+$i > 0){
                            if($k+$i==$pn)
                              $pagLink .= "<li class='active'><a href='index.php?page=".($k+$i)."'>".($k+$i)."</a></li>";
                            else
                              $pagLink .= "<li><a href='index.php?page=".($k+$i)."'>".($k+$i)."</a></li>";
                          }
                      };
                      echo $pagLink;
                      if($pn == $total_pages){
                        echo "<li class='active'><a href='index.php?page=".$total_pages."'> ".$total_pages." </a></li>";
                      }
                      if($pn<$total_pages){
                          if(($total_pages-$pn) > 2){
                            echo "<li><a href='index.php?page=".($pn+2)."'> ... </a></li>";
                          }
                          echo "<li><a href='index.php?page=".$total_pages."'> ".$total_pages." </a></li>";
                          echo "<a href='index.php?page=".($pn+1)."'><li class='page-btn'> > </li></a>";

                      }

                    }
                  }
                    ?>
                  </ul>
                 </div>
              </div>
            </div>
        </div>

<?php include '../include/footer.php';?>
